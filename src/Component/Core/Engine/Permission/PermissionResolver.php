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

use Eki\NRW\Component\Base\Engine\Permission\PermissionResolver as PermissionResolverInterface;
use Eki\NRW\Component\Base\Engine\Engine as EngineInterface;
use Eki\NRW\Component\Base\Engine\Permission\Reference;

use Eki\NRW\Component\SPBase\Permission\Limitation\Type as LimitationType;
use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Handler as RoleHandler;

use Eki\NRW\Mdl\Permission\Permission\Resolver;

/**
 * Core implementation of PermissionResolver interface.
 */
class PermissionResolver extends Resolver implements PermissionResolverInterface
{
    /**
     * Counter for the current sudo nesting level {@see sudo()}.
     *
     * @var int
     */
    private $sudoNestingLevel = 0;

    /**
     * @param \Eki\NRW\Component\Core\Engine\Permission\RoleDomainMapper $roleDomainMapper 
     * @param \Eki\NRW\Component\Core\Engine\Permission\LimitationService $limitationService
     * @param \Eki\NRW\Component\Base\Persistence\Permission\Role\Handler $roleHandler
     * @param array $policyMap Map of system configured policies, for validation usage.
     */
    public function __construct(
    	RoleDomainMapper $roleDomainMapper,
        LimitationService $limitationService,
        RoleHandler $roleHandler,
        array $policyMap = []
    ) {
        $helper = new Helper($roleDomainMapper, $limitationService, $roleHandler);
        
        parent::__construct($helper, $policyMap);
    }

    public function hasAccess($service, $function, Reference $reference = null)
    {
        // Full access if sudo nesting level is set by {@see sudo()}
        if ($this->sudoNestingLevel > 0) 
        {
            return true;
        }
        
        return parent::hasAccess($service, $function, $reference);
    }

    /**
     * @internal For internal use only, do not depend on this method.
     *
     * Allows API execution to be performed with full access sand-boxed.
     *
     * The closure sandbox will do a catch all on exceptions and rethrow after
     * re-setting the sudo flag.
     *
     * Example use:
     *     $location = $engine->sudo(
     *         function ( Engine $repo ) use ( $locationId )
     *         {
     *             return $repo->getLocationService()->loadLocation( $locationId )
     *         }
     *     );
     *
     *
     * @param \Closure $callback
     * @param \Eki\NRW\Component\Base\Engine\Engine $outerEngine
     *
     * @throws \RuntimeException Thrown on recursive sudo() use.
     * @throws \Exception Re throws exceptions thrown inside $callback
     *
     * @return mixed
     */
    public function sudo(\Closure $callback, EngineInterface $outerEngine)
    {
        ++$this->sudoNestingLevel;
        try {
            $returnValue = $callback($outerEngine);
        } catch (Exception $e) {
            --$this->sudoNestingLevel;
            throw $e;
        }

        --$this->sudoNestingLevel;

        return $returnValue;
    }
}
