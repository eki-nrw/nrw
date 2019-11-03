<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Helper;

use Eki\NRW\Component\Core\Repository\Values\User\Policy;
use Eki\NRW\Component\Core\Repository\Values\User\Role;
use Eki\NRW\Component\Base\Repository\Values\User\Role as APIRole;
use Eki\NRW\Component\Base\Repository\Values\User\RoleCreateStruct as APIRoleCreateStruct;
use Eki\NRW\Component\Core\Repository\Values\User\UserRoleAssignment;
use Eki\NRW\Component\Core\Repository\Values\User\UserGroupRoleAssignment;
use Eki\NRW\Component\Base\Permission\Role\Limitation;
use Eki\NRW\Component\Base\Permission\User\User;
use Eki\NRW\Component\Base\Permission\User\UserGroup;
use Eki\NRW\Component\Base\Persistence\Permission\User\Policy as PSPolicy;
use Eki\NRW\Component\Base\Persistence\Permission\User\RoleAssignment as PSRoleAssignment;
use Eki\NRW\Component\Base\Persistence\Permission\User\Role as PSRole;

/**
 * Internal service to map Role objects between API and PS values.
 *
 * @internal Meant for internal use by Repository.
 */
class RoleDomainMapper
{
    /**
     * @var \Eki\NRW\Component\Core\Repository\Helper\LimitationService
     */
    protected $limitationService;

    /**
     * @param \Eki\NRW\Component\Core\Repository\Helper\LimitationService $limitationService
     */
    public function __construct(LimitationService $limitationService)
    {
        $this->limitationService = $limitationService;
    }

    /**
     * Maps provided PS Role value object to API Role value object.
     *
     * @param \Eki\NRW\Component\Base\Persistence\User\Role $role
     *
     * @return \Eki\NRW\Component\Base\Repository\Values\User\Role
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
     * Maps provided PS Policy value object to API Policy value object.
     *
     * @param \Eki\NRW\Component\Base\Persistence\User\Policy $psPolicy
     *
     * @return \Eki\NRW\Component\Base\Repository\Values\User\Policy|\Eki\NRW\Component\Base\Repository\Values\User\PolicyDraft
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
     * Builds the API UserRoleAssignment object from provided PS RoleAssignment object.
     *
     * @param \Eki\NRW\Component\Base\Persistence\User\RoleAssignment $psRoleAssignment
     * @param \Eki\NRW\Component\Base\Repository\Values\User\User $user
     * @param \Eki\NRW\Component\Base\Repository\Values\User\Role $role
     *
     * @return \Eki\NRW\Component\Base\Repository\Values\User\UserRoleAssignment
     */
    public function buildDomainUserRoleAssignmentObject(PSRoleAssignment $psRoleAssignment, User $user, APIRole $role)
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
     * Builds the API UserGroupRoleAssignment object from provided PS RoleAssignment object.
     *
     * @param \Eki\NRW\Component\Base\Persistence\User\RoleAssignment $psRoleAssignment
     * @param \Eki\NRW\Component\Base\Repository\Values\User\UserGroup $userGroup
     * @param \Eki\NRW\Component\Base\Repository\Values\User\Role $role
     *
     * @return \Eki\NRW\Component\Base\Repository\Values\User\UserGroupRoleAssignment
     */
    public function buildDomainUserGroupRoleAssignmentObject(PSRoleAssignment $psRoleAssignment, UserGroup $userGroup, APIRole $role)
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
     * @param \Eki\NRW\Component\Base\Repository\Values\User\Limitation[] $limitations
     *
     * @return \Eki\NRW\Component\Base\Persistence\User\Policy
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
