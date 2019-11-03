<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Permission;

use Eki\NRW\Component\Core\Engine\Permission\Role\Policy;
use Eki\NRW\Component\Core\Engine\Permission\Role\Role;

use Eki\NRW\Component\Base\Engine\Permission\Role\Role as BaseRole;

use Eki\NRW\Component\Base\Repository\Values\User\RoleCreateStruct as BaseRoleCreateStruct;

use Eki\NRW\Component\Core\Engine\Permission\User\UserRoleAssignment;
use Eki\NRW\Component\Core\Engine\Permission\User\UserGroupRoleAssignment;

use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation;
use Eki\NRW\Component\Base\Engine\Permission\User\User;
use Eki\NRW\Component\Base\Engine\Permission\User\Group as UserGroup;

use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Policy as PSPolicy;
use Eki\NRW\Component\SPBase\Persistence\Permission\Role\RoleAssignment as PSRoleAssignment;
use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role as PSRole;

/**
 * Internal service to map Role objects between API and PS values.
 *
 * @internal Meant for internal use by Repository.
 */
class RoleDomainMapper
{
    /**
     * @var \Eki\NRW\Component\Core\Engine\Permission\LimitationService
     */
    protected $limitationService;

    /**
     * @param \Eki\NRW\Component\Core\Engine\Permission\LimitationService $limitationService
     */
    public function __construct(LimitationService $limitationService)
    {
        $this->limitationService = $limitationService;
    }

    /**
     * Maps provided PS Role value object to API Role value object.
     *
     * @param \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role $role
     *
     * @return \Eki\NRW\Component\Base\Engine\Permission\Role\Role
     */
    public function buildDomainRoleObject(PSRole $role)
    {
        $rolePolicies = array();
        foreach ($role->policies as $psPolicy) {
            $rolePolicies[] = $this->buildDomainPolicyObject($psPolicy);
        }

        return new Role(
            array(
                'id' => $role->id,
                'identifier' => $role->identifier,
                'status' => $role->status,
                'policies' => $rolePolicies,
            )
        );
    }

    /**
     * Maps provided PS Policy value object to Base Policy value object.
     *
     * @param \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Policy $psPolicy
     *
     * @return \Eki\NRW\Component\Base\Engine\Permission\Role\Policy
     */
    public function buildDomainPolicyObject(PSPolicy $psPolicy)
    {
        $policyLimitations = array();
        if ($psPolicy->service !== '*' && $psPolicy->function !== '*' && $psPolicy->limitations !== '*') {
            foreach ($psPolicy->limitations as $identifier => $values) {
                $policyLimitations[] = $this->limitationService->getLimitationType($identifier)->buildValue($values);
            }
        }

        $policy = new Policy(
            array(
                'id' => $psPolicy->id,
                'roleId' => $psPolicy->roleId,
                'service' => $psPolicy->service,
                'function' => $psPolicy->function,
                'limitations' => $policyLimitations,
            )
        );

        // Original ID is set on PS policy, which means that it's a draft.
        if ($psPolicy->originalId) {
            $policy = new PolicyDraft(['innerPolicy' => $policy, 'originalId' => $psPolicy->originalId]);
        }

        return $policy;
    }

    /**
     * Builds the Base UserRoleAssignment object from provided PS RoleAssignment object.
     *
     * @param \Eki\NRW\Component\SPBase\Persistence\Permission\Role\RoleAssignment $psRoleAssignment
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\User $user
     * @param \Eki\NRW\Component\Base\Engine\Permission\Role\Role $role
     *
     * @return \Eki\NRW\Component\Base\Engine\Permission\User\UserRoleAssignment
     */
    public function buildDomainUserRoleAssignmentObject(PSRoleAssignment $psRoleAssignment, User $user, BaseRole $role)
    {
        $limitation = null;
        if (!empty($psRoleAssignment->limitationIdentifier)) {
            $limitation = $this
                ->limitationService
                ->getLimitationType($psRoleAssignment->limitationIdentifier)
                ->buildValue($psRoleAssignment->values);
        }

        return new UserRoleAssignment(
            array(
                'id' => $psRoleAssignment->id,
                'limitation' => $limitation,
                'role' => $role,
                'user' => $user,
            )
        );
    }

    /**
     * Builds the Base UserGroupRoleAssignment object from provided PS RoleAssignment object.
     *
     * @param \Eki\NRW\Component\SPBase\Persistence\Permission\Role\RoleAssignment $psRoleAssignment
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\Group $userGroup
     * @param \Eki\NRW\Component\Base\Engine\Permission\Role\Role $role
     *
     * @return \Eki\NRW\Component\Base\Repository\Values\User\UserGroupRoleAssignment
     */
    public function buildDomainUserGroupRoleAssignmentObject(PSRoleAssignment $psRoleAssignment, UserGroup $userGroup, BaseRole $role)
    {
        $limitation = null;
        if (!empty($psRoleAssignment->limitationIdentifier)) {
            $limitation = $this
                ->limitationService
                ->getLimitationType($psRoleAssignment->limitationIdentifier)
                ->buildValue($psRoleAssignment->values);
        }

        return new UserGroupRoleAssignment(
            array(
                'id' => $psRoleAssignment->id,
                'limitation' => $limitation,
                'role' => $role,
                'userGroup' => $userGroup,
            )
        );
    }

    /**
     * Creates PS Policy value object from provided service, function and limitations.
     *
     * @param string $service
     * @param string $function
     * @param \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation[] $limitations
     *
     * @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Policy
     */
    public function buildPersistencePolicyObject($service, $function, array $limitations)
    {
        $limitationsToCreate = '*';
        if ($service !== '*' && $function !== '*' && !empty($limitations)) {
            $limitationsToCreate = array();
            foreach ($limitations as $limitation) {
                $limitationsToCreate[$limitation->getIdentifier()] = $limitation->limitationValues;
            }
        }

        return new PSPolicy(
            array(
                'service' => $service,
                'function' => $function,
                'limitations' => $limitationsToCreate,
            )
        );
    }
}
