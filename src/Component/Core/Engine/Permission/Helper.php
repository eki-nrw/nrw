<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\Permission\Permission;

use Eki\NRW\Mdl\Permission\RoleAssignmentInterface;
use Eki\NRW\Mdl\Permission\PolicyInterface;
use Eki\NRW\Mdl\Permission\Limitation\Type;

/**
 * Permission Helper implmentation 
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class Helper implements HelperInterface
{
	/**
     * @var \Eki\NRW\Component\Core\Engine\Permission\RoleDomainMapper
     */
    private $roleDomainMapper;
    
    /**
     * @var \Eki\NRW\Component\Core\Engine\Permission\LimitationService
     */
    private $limitationService;

    /**
     * @var \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Handler
     */
    private $roleHandler;
	
	public function __construct(
		RoleDomainMapper $roleDomainMapper,
		LimitationService $limitationService,
		RoleHandler $roleHandler
	)
	{
		$this->roleDomainMapper = $roleDomainMapper;
		$this->limitationService = $limitationService;
		$this->roleHandler = $roleHandler;
	}
	
	/**
	* Load role asignments by role reference
	* 
	* @param mixed $roleRef
	* @param bool $inherit 
	* 
	* @return RoleAssignmentInterface
	*/
	public function loadRoleAssignments($roleRef, $inherit = false)
	{
		return $this->roleHandler->loadRoleAssignmentsByGroupId($roleRef, $inherit);		
	}

    /**
     * Returns the LimitationType registered with the given identifier.
     *
     * Returns the correct implementation of API Limitation value object
     * based on provided identifier
     *
     * @param string $identifier
     *
     * @throws \InvalidArgumentException
     *
     * @return \Eki\NRW\Mdl\Permission\Limitation\Type
     */
    public function getLimitationType($identifier)
    {
		return $this->limitationService->getLimitationType($identifier);
	}

    /**
	* 
	* @param PolicyInterface $policy
	* 
	* @return PolicyInterface
	*/
    public function buildDomainPolicyObject(PolicyInterface $policy)
    {
		return $this->roleDomainMapper->buildDomainPolicyIObject($policy);		
	}
}
