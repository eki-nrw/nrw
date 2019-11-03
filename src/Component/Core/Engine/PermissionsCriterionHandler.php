<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine;

use Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion;
use Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion\LogicalAnd;
use Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion\LogicalOr;
use Eki\NRW\Component\Base\Engine\Values\Actor\Limitation;
use Eki\NRW\Component\Base\Engine\PermissionResolver as PermissionResolverInterface;
use Eki\NRW\Component\Core\Engine\Helper\LimitationService;
use RuntimeException;

/**
 * Handler for permissions Criterion.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class PermissionsCriterionHandler
{
    /**
     * @var \Eki\NRW\Component\Base\Engine\PermissionResolver
     */
    private $permissionResolver;

    /**
     * @var \Eki\NRW\Component\Core\Engine\Helper\LimitationService
     */
    private $limitationService;

    /**
     * Constructor.
     *
     * @param \Eki\NRW\Component\Base\Engine\PermissionResolver $permissionResolver
     * @param \Eki\NRW\Component\Core\Engine\Helper\LimitationService $limitationService
     */
    public function __construct(
        PermissionResolverInterface $permissionResolver,
        LimitationService $limitationService
    ) {
        $this->permissionResolver = $permissionResolver;
        $this->limitationService = $limitationService;
    }

    /**
     * Adds content, read Permission criteria if needed and return false if no access at all.
     *
     * @uses ::getPermissionsCriterion()
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\Content\Query\Criterion $criterion
     *
     * @return bool|\Eki\NRW\Component\Base\Engine\Values\Content\Query\Criterion
     */
    public function addPermissionsCriterion(Criterion &$criterion)
    {
        $permissionCriterion = $this->getPermissionsCriterion();
        if ($permissionCriterion === true || $permissionCriterion === false) {
            return $permissionCriterion;
        }

        // Merge with original $criterion
        if ($criterion instanceof LogicalAnd) {
            $criterion->criteria[] = $permissionCriterion;
        } else {
            $criterion = new LogicalAnd(
                array(
                    $criterion,
                    $permissionCriterion,
                )
            );
        }

        return true;
    }

    /**
     * Get content-read Permission criteria if needed and return false if no access at all.
     *
     * @uses \Eki\NRW\Component\Base\Engine\PermissionResolver::hasAccess()
     *
     * @throws \RuntimeException If empty array of limitations are provided from hasAccess()
     *
     * @param string $service
     * @param string $function
     *
     * @return bool|\Eki\NRW\Component\Base\Engine\Values\Content\Query\Criterion
     */
    public function getPermissionsCriterion($service, $function)
    {
        $permissionSets = $this->permissionResolver->hasAccess($service, $function);
        if ($permissionSets === false || $permissionSets === true) {
            return $permissionSets;
        }

        if (empty($permissionSets)) {
            throw new RuntimeException("Got an empty array of limitations from hasAccess( '{$service}', '{$function}' )");
        }

        /*
         * RoleAssignment is a OR condition, so is policy, while limitations is a AND condition
         *
         * If RoleAssignment has limitation then policy OR conditions are wrapped in a AND condition with the
         * role limitation, otherwise it will be merged into RoleAssignment's OR condition.
         */
        $currentUserRef = $this->permissionResolver->getCurrentUserReference();
        $roleAssignmentOrCriteria = array();
        foreach ($permissionSets as $permissionSet) {
            // $permissionSet is a RoleAssignment, but in the form of role limitation & role policies hash
            $policyOrCriteria = array();
            /**
             * @var \Eki\NRW\Component\Base\Engine\Values\User\Policy
             */
            foreach ($permissionSet['policies'] as $policy) {
                $limitations = $policy->getLimitations();
                if ($limitations === '*' || empty($limitations)) {
                    // Given policy gives full access, optimize away all role policies (but not role limitation if any)
                    // This should be optimized on create/update of Roles, however we keep this here for bc with older data
                    $policyOrCriteria = [];
                    break;
                }

                $limitationsAndCriteria = array();
                foreach ($limitations as $limitation) {
                    $type = $this->limitationService->getLimitationType($limitation->getIdentifier());
                    $limitationsAndCriteria[] = $type->getCriterion($limitation, $currentUserRef);
                }

                $policyOrCriteria[] = isset($limitationsAndCriteria[1]) ?
                    new LogicalAnd($limitationsAndCriteria) :
                    $limitationsAndCriteria[0];
            }

            /**
             * Apply role limitations if there is one.
             *
             * @var \Eki\NRW\Component\Base\Engine\Values\User\Limitation[]
             */
            if ($permissionSet['limitation'] instanceof Limitation) {
                // We need to match both the limitation AND *one* of the policies, aka; roleLimit AND policies(OR)
                $type = $this->limitationService->getLimitationType($permissionSet['limitation']->getIdentifier());
                if (!empty($policyOrCriteria)) {
                    $roleAssignmentOrCriteria[] = new LogicalAnd(
                        array(
                            $type->getCriterion($permissionSet['limitation'], $currentUserRef),
                            isset($policyOrCriteria[1]) ? new LogicalOr($policyOrCriteria) : $policyOrCriteria[0],
                        )
                    );
                } else {
                    $roleAssignmentOrCriteria[] = $type->getCriterion($permissionSet['limitation'], $currentUserRef);
                }
            } elseif (!empty($policyOrCriteria)) {
                // Otherwise merge $policyOrCriteria into $roleAssignmentOrCriteria
                // There is no role limitation, so any of the policies can globally match in the returned OR criteria
                $roleAssignmentOrCriteria = empty($roleAssignmentOrCriteria) ?
                    $policyOrCriteria :
                    array_merge($roleAssignmentOrCriteria, $policyOrCriteria);
            }
        }

        if (empty($roleAssignmentOrCriteria)) {
            return false;
        }

        return isset($roleAssignmentOrCriteria[1]) ?
            new LogicalOr($roleAssignmentOrCriteria) :
            $roleAssignmentOrCriteria[0];
    }
}
