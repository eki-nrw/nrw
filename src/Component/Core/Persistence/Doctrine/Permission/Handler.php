<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Permission;

use Eki\NRW\Component\SPBase\Persistence\Permission\Handler as HandlerInterface;

use Eki\NRW\Component\Core\Persistence\Doctrine\GroupHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Permission\User\Handler as UserHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Permission\Role\Handler as RoleHandler;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;
use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Handler extends GroupHandler implements HandlerInterface
{
	/**
	* @var Eki\NRW\Component\Core\Persistence\Doctrine\Permission\User\Handler
	*/
	protected $userHandler;

	/**
	* @var Eki\NRW\Component\Core\Persistence\Doctrine\Permission\Role\Handler
	*/
	protected $roleHandler;
	
	/**
	* Returns user handler
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\User\Handler
	*/
	public function userHandler()
	{
		if ($this->userHandler === null)
		{
			$this->userHandler = new UserHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->extractRegistry(array('user', 'group', 'user_group_assignment'))
			);
		}
		
		return $this->userHandler;
	}

	/**
	* Returns role handler
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Handler
	*/
	public function roleHandler()
	{
		if ($this->roleHandler === null)
		{
			$this->roleHandler = new RoleHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->extractRegistry(array('role', 'policy', 'role_assignment'))
			);
		}
		
		return $this->roleHandler;
	}

	/**
	* Returns role assignment handler
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\RoleAssignment\Handler
	*/
	protected function roleAssignmentHandler()
	{
		return $this->roleHandler()->roleAssignmentHandler();
	}

	/**
	* @inheritdoc
	* 
	*/
	public function assignRoleToUser($userId, $roleId, array $limitations = null)
	{
		if (null === ($user = $this->userHandler->loadUser($userId)))
			throw new NotFoundException("User", $userId);
		
		if (null === ($role = $this->roleHandler()->loadRole($roleId)))
			throw new NotFoundException("Role", $roleId);

		try 
		{
			$roleAssignment = $this->roleAssignmentHandler()->create("user", $role, $user);
		}
		catch(\Exception $e)
		{
			throw $e;				
		}
		
		$this->roleAssignmentHandler()->update($roleAssignment);
		
		return $roleAssignment;
	}
	
	public function unAssignRoleFromUser($userId, $roleId)
	{
		if (null !== ($roleAssignment = $this->getObjectFromCache($roleId, $userId)))
		{
			$this->clearObjectFromCache($roleAssignment, $userId);
			$this->roleAssignmentHandler()->delete($roleAssignment);
		}
		else
		{
			if (null === ($roleAssignment = $this->roleAssignmentHandler()->loadByRoleId($roleId)))
				throw new NotFoundException("Role", array('id' => $roleId));
				
			$this->roleAssignmentHandler()->delete($roleAssignment);
		}
	}
	
	public function assignRoleToUserGroup($userGroupId, $roleId, array $limitations = nul)
	{
		if (null === ($userGroup = $this->userHandler->loadGroup($userGroupId)))
			throw new NotFoundException("UserGroup", $userGroupId);
		
		if (null === ($role = $this->roleHandler()->loadRole($roleId)))
			throw new NotFoundException("Role", $roleId);

		try 
		{
			$roleAssignment = $this->roleAssignmentHandler()->create("group", $role, $userGroup);
		}
		catch(\Exception $e)
		{
			throw $e;				
		}
		
		$this->roleAssignmentHandler()->update($roleAssignment);
		
		return $roleAssignment;
	}
	
	public function unAssignRoleFromUserGroup($userGroupId, $roleId)
	{
		if (null !== ($roleAssignment = $this->getObjectFromCache($roleId, $userGroupId)))
		{
			$this->roleAssignmentHandler()->delete($roleAssignment);
		}
		else
		{
			if (null === ($roleAssignment = $this->roleAssignmentHandler()->loadByRoleId($roleId)))
				throw new NotFoundException("Role", array('id' => $roleId));
				
			$this->roleAssignmentHandler()->delete($roleAssignment);
		}
	}
	
    /**
     * Loads roles assignments to a user/group.
     *
     * Role Assignments with same roleId and limitation Identifier will be merged together into one.
     *
     * @param mixed $groupId 
     * @param bool $inherit If true also return inherited role assignments from user groups.
     *
     * @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\RoleAssignment[]
     */
    public function loadRoleAssignmentsByGroupId($groupId, $inherit = false)
    {
		
	}
}
