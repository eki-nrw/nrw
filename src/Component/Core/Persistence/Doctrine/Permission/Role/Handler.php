<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Permission\Role;

use Eki\NRW\Component\SPBase\Persistence\BaseHandler;
use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Handler as HandlerInterface;

use Eki\NRW\Component\Core\Persistence\Doctrine\Permission\Role\Role\Handler as RoleHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Permission\Role\Policy\Handler as PolicyHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Permission\Role\RoleAssignment\Handler as RoleAssignmentHandler;

use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role as PSRole;
use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Policy as PSPolicy;
use Eki\NRW\Component\SPBase\Persistence\Permission\Role\RoleAssignment as PSRoleAssignment;

use Eki\NRW\Component\Core\Persistence\Permission\Role\Role;
use Eki\NRW\Component\Core\Persistence\Permission\Role\Policy;
use Eki\NRW\Component\Core\Persistence\Permission\Role\RoleAssignment;

use Eki\NRW\Component\Base\Core\Exceptions\NotFoundException;
use Eki\NRW\Component\Base\Core\Exceptions\InvalidArgumentException;

use Symfony\Component\Cache\Adapter\AdapterInteface as Cache;
use Doctrine\Common\Persistence\ObjectManager;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Handler extends GroupHandler implements HandlerInterface
{
	/**
	* @var RoleHandler
	*/
	protected $roleHandler;

	/**
	* @var PolicyHandler
	*/
	protected $policyHandler;
	
	/**
	* @var RoleAssignmentHandler
	*/
	protected $roleAssignmentHandler;

	/**
	* Return Role Handler
	* 
	* @return RoleHandler
	*/	
	protected function roleHandler()
	{
		if ($this->roleHandler === null)
		{
			$this->roleHandler = new RoleHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('role')
			);
		}
		
		return $this->roleHandler;
	}

	/**
	* Return Policy Handler
	* 
	* @return PolicyHandler
	*/
	protected function policyHandler()
	{
		if ($this->policyHandler === null)
		{
			$this->policyHandler = new PolicyHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('policy')
			);
		}
		
		return $this->policyHandler;
	}

	/**
	* Return Role Assignment Handler
	* 
	* @return RoleAssignmentHandler
	*/	
	protected function roleAssignmentHandler()
	{
		if ($this->roleAssignmentHandler === null)
		{
			$this->roleAssignmentHandler = new RoleAssignmentHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('role_assignment')
			);
		}
		
		return $this->roleAssignmentHandler;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function createRole($identifier)
	{
		return $this->roleHandler()->createRole($identifier);
	}
	
	/**
	* Load role object
	* 
	* @param int|string $roleId
	* 
	* @return \Eki\NRW\Component\SPBase\Permission\Role\Role
	*/
	public function loadRole($roleId)
	{
		return $this->roleHandler()->loadRole($roleId);
	}

	/**
	* Load role by identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\SPBase\Permission\Role\Role
	*/
	public function loadRoleByIdentifier($identifier)
	{
		return $this->roleHandler()->loadRoleByIdentifier($identifier);
	}
	
	/**
	* Load all roles
	* 
	* @return \Eki\NRW\Component\SPBase\Permission\Role\Role[]
	*/
	public function loadRoles()
	{
		return $this->roleHandler()->loadRoles();
	}

    /**
     * Loads role assignment for specified assignment ID.
     *
     * @param mixed $roleAssignmentId
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException If role assignment is not found
     *
     * @return \eZ\Publish\SPI\Persistence\User\RoleAssignment
     */
    public function loadRoleAssignment($roleAssignmentId)
	{
		return $this->roleAssignmentHandler()->loadRoleAssignment($roleAssignmentId);
	}
    
    /**
     * Loads roles assignments Role.
     *
     * Role Assignments with same roleId and limitationIdentifier will be merged together into one.
     *
     * @param mixed $roleId
     *
     * @return \Eki\NRW\Component\SPBase\Permission\Role\RoleAssignment[]
     */
    public function loadRoleAssignmentsByRoleId($roleId)
    {
    	return $this->roleAssignmentHandler()->loadAllByRoleId($roleId);
	}

	/**
	* @inheritdoc
	* 
	*/
    public function loadRoleAssignmentsByGroupId($groupId, $inherit = false)
    {
		
	}

	/**
	* @inheritdoc
	* 
	*/
	public function deleteRole(PSRole $role)
	{
		$policyHandler = $this->policyHandler();
		foreach($role->policies as $policy)
		{
			$policyHandler->deletePolicy($policy);
		}
		
		$this->roleHandler()->deleteRole($role);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function updateRole(PSRole $role)
	{
		$this->roleHandler()->updateRole($role);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function createPolicy($service, $function, array $limitations = [])
	{
		return $this->policyHandler()->createPolicy($service, $function, $limitations);
	}

	/**
	* @inheritdoc
	* 
	*/
    public function addPolicy($roleId, PSPolicy $policy)
	{
		$psRole = $this->loadRole($roleId);
		$psPolicy = $this->createPolicy($policy->service, $policy->function, $policy->limiatations);
		
		$psRole->policies[] = $psPolicy;
		$psPolicy->roleId = $roleId;
		
		$this->updateRole($psRole);
		$this->updatePolicy($psPolicy);
		
		return $this->loadPolicy($psPolicy->getId());
	}

	/**
	* @inheritdoc
	* 
	*/
	public function loadPolicy($policyId)
	{
		return $this->policyHandler()->loadPolicy($policyId);	
	}

	/**
	* @inheritdoc
	* 
	*/
	public function deletePolicy(PSPolicy $policy)
	{
		$roleNeedUpdate = false;
		$psRole = $this->loadRole($policy->roleId);
		$psPolicies = $psRole->policies;
		foreach($psPolicies as $psPolicy)
		{
			if ($psPolicy->getId() === $policy->getId())
			{
				$restPolicies = array_diff($psPolicies, array($psPolicy));
				$psRole->policies = $restPolicies;
				$roleNeedUpdate = true;
				break;
			}
		}

		if (!$roleNeedUpdate)
			throw new InvalidArgumentException('policy', 'Policy is not belongs to role.');
		
		$this->policyHandler()->deletePolicy($policy);
		$this->updateRole($psRole);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function updatePolicy(PSPolicy $policy)
	{
		$this->policyHandler()->updatePolicy($policy);
	}
}
