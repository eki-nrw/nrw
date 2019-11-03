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

use Eki\NRW\Component\Base\Engine\Permission\PermissionResolver as BasePermissionResolver;
use Eki\NRW\Component\Base\Engine\Permission\PermissionCriterionResolver as BasePermissionCriterionResolver;
use Eki\NRW\Component\Base\Engine\Permission\ReferenceInterface;
use Eki\NRW\Component\Base\Engine\Engine as EngineInterface;

use Closure;
use Exception;

/**
 * Cache implementation of PermissionResolver and PermissionCriterionResolver interface.
 *
 * Implements both interfaces as the cached permission criterion lookup needs to be
 * expired when a different user is set as current users in the system.
 *
 * Cache is only done for content/read policy, as that is the one needed by search service.
 *
 * The logic here uses a cache TTL of a few seconds, as this is in-memory cache we are not
 * able to know if any other concurrent user might be changing permissions.
 */
class CachedPermissionService implements BasePermissionResolver, BasePermissionCriterionResolver
{
    /**
     * @var \Eki\NRW\Component\Base\Engine\Permission\PermissionResolver
     */
    private $permissionResolver;

    /**
     * @var \Eki\NRW\Component\Base\Engine\Permission\PermissionCriterionResolver
     */
    private $permissionCriterionResolver;

    /**
     * @var int
     */
    private $cacheTTL;

	/**
     * Counter for the current sudo nesting level {@see sudo()}.
     *
     * @var int
     */
    private $sudoNestingLevel = 0;
    
    /**
     * Cached value for current user's getCriterion() result.
     *
     * Value is null if not yet set or cleared.
     *
     * @var bool|\Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion
     */
    private $permissionCriterion;

    /**
     * Cache time stamp.
     *
     * @var int
     */
    private $permissionCriterionTs;

    /**
     * CachedPermissionService constructor.
     *
     * @param \Eki\NRW\Component\Base\Engine\Permission\PermissionResolver $permissionResolver
     * @param \Eki\NRW\Component\Base\Engine\Permission\PermissionCriterionResolver $permissionCriterionResolver
     * @param int $cacheTTL By default set to 5 seconds, should be low to avoid to many permission exceptions on long running requests / processes (even if tolerant search service should handle that)
     */
    public function __construct(
        BasePermissionResolver $permissionResolver,
        BasePermissionCriterionResolver $permissionCriterionResolver,
        $cacheTTL = 5
    ) {
        $this->permissionResolver = $permissionResolver;
        $this->permissionCriterionResolver = $permissionCriterionResolver;
        $this->cacheTTL = $cacheTTL;
    }

    public function getCurrentReference()
    {
        return $this->permissionResolver->getCurrentReference();
    }

    public function setCurrentReference(ReferenceInterface $reference)
    {
        $this->permissionCriterion = null;

        return $this->permissionResolver->setCurrentReference($reference);
    }

    public function hasAccess($service, $function, ReferenceInterface $reference = null)
    {
        return $this->permissionResolver->hasAccess($service, $function, $reference);
    }

    public function canUser($service, $function, $object, array $targets = [])
    {
        return $this->permissionResolver->canUser($service, $function, $object, $targets);
    }

    public function getPermissionsCriterion($service, $function)
    {
        // We only cache content/read lookup as those are the once frequently done, and it's only one we can safely
        // do that won't harm the system if it becomes stale (but user might experience permissions exceptions if it do)
        if (
        	($service !== 'agent' and $service !== 'resource')
        	|| $function !== 'read' 
        	|| $this->sudoNestingLevel > 0
        ) 
        {
            return $this->permissionCriterionResolver->getPermissionsCriterion($service, $function);
        }

        if ($this->permissionCriterion !== null) {
            // If we are still within the cache TTL, then return the cached value
            if ((time() - $this->permissionCriterionTs) < $this->cacheTTL) {
                return $this->permissionCriterion;
            }
        }

        $this->permissionCriterionTs = time();
        $this->permissionCriterion = $this->permissionCriterionResolver->getPermissionsCriterion($service, $function);

        return $this->permissionCriterion;
    }

    /**
     * @internal For internal use only, do not depend on this method.
     */
    public function sudo(Closure $callback, EngineInterface $outerEngine)
    {
		++$this->sudoNestingLevel;

        try 
        {
            $returnValue = $this->permissionResolver->sudo($callback, $outerRepository);
        } 
        catch (Exception $e) 
        {
            --$this->sudoNestingLevel;
            throw $e;
        }

        --$this->sudoNestingLevel;
        return $returnValue;    
    }
}
