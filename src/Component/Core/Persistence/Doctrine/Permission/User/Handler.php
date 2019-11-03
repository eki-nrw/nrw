<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Permission\User;

use Eki\NRW\Component\SPBase\Persistence\Permission\User\Handler as HandlerInterface;
use Eki\NRW\Component\SPBase\Persistence\Permission\User\User as BaseUser;
use Eki\NRW\Component\SPBase\Persistence\Permission\User\Group as BaseGroup;
use Eki\NRW\Component\SPBase\Persistence\Permission\User\UserGroupAssignment as BaseUserGroupAssignment;

use Eki\NRW\Component\Core\Persistence\Doctrine\GroupHandler;
use Eki\NRW\Component\Core\Persistence\Permission\User\User;
use Eki\NRW\Component\Core\Persistence\Permission\User\Group;
use Eki\NRW\Component\Core\Persistence\Permission\User\UserGroupAssignment;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Eki\NRW\Component\Core\Persistence\Doctrine\Permission\User\User\Handler as UserHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Permission\User\Group\Handler as GroupHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Permission\User\Assignment\Handler as AssignmentHandler;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Handler extends GroupHandler implements HandlerInterface
{
	/**
	* @var UserHandler
	*/
	protected $userHandler;

	/**
	* @var GroupHandler
	*/	
	protected $groupHandler;
	
	/**
	* @var AssignmentHandler
	*/
	protected $assignmentHandler;
	
	/**
	* @inheritdoc
	* 
	*/
	public function createUser(BaseUser $user)
	{
		return $this->userHandler()->createUser($user);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function loadUser($userId)
	{
		return $this->userHandler()->loadUser($userId);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function loadUserByLogin($login)
	{
		return $this->userHandler()->loadUserByLogin($login);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function updateUser(BaseUser $user)
	{
		$this->userHandler()->updateUser($user);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function deleteUser(BaseUser $user)
	{
		$this->userHandler()->deleteUser($user);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function assignUserToGroup(BaseUser $user, BaseGroup $group)
	{
		if (null === $this->loadUser($user->getId()))
            throw new NotFoundException('User', $user->getId());
		if (null === $this->loadGroup($group->getId()))
            throw new NotFoundException('Group', $group->getId());
            
		if (null !== $this->assignmentHandler()->findAssignment($user, $group))
			throw new InvalidArgumentException("user/group", "Already exists user/group assignment.");
			
		$userGroupAssignment = $this->assignmentHandler()->createAssignment($user, $group);
		
		return $this->assignmentHandler()->loadAssignment($userGroupAssignment->getId());
	}

	/**
	* @inheritdoc
	* 
	*/
	public function unassignUserFromGroup(User $user, Group $group)
	{
		if (null === $this->loadUser($user->getId()))
            throw new NotFoundException('User', $user->getId());
		if (null === $this->loadGroup($group->getId()))
            throw new NotFoundException('Group', $group->getId());

		$userGroupAssingnment = $this->assignment()->findAssignment($user, $group);
		if (null === $userGroupAssingnment)
		{
            throw new NotFoundException(
            	'UserGroupAssignment', 
            	array(
            		'user_id' => $user->getId(), 
            		'group_id' => $group->getId()
            	)
            );
		}

		$this->assignmentHandler()->deleteAssignment($usergroupAssingnment);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getSubGroups(Group $group, $limit = null, $offset = null)
	{
		//???!!!
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getGroupsOfUser(BaseUser $user, $limit = null, $offset = null)
	{
		return $this->assignmentHandler()->findGroupsOfUser($user, $limit, $offset);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function createGroup(BaseGroup $parentGroup = null)
	{
		return $this->groupHandler()->createGroup($parentGroup);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function loadGroup($groupId)
	{
		return $this->groupHandler()->loadGroup($groupId);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function deleteGroup(BaseGroup $group)
	{
		$this->groupHandler()->deleteGroup($group);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function updateGroup(BaseGroup $group)
	{
		$this->groupHandler()->updateGroup($group);
	}

	/**
	* @inheritdoc
	* 
	*/
    public function moveGroup(BaseGroup $group, BaseGroup $newParent)
    {
    	return $this->groupHandler()->moveGroup($group, $newParent);
	}

	protected function userHandler()
	{
		if ($this->userHandler !== null)
		{
			$this->userHandler = new UserHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('user')
			);
		}
		
		return $this->userHandler;		
	}
	
	protected function groupHandler()
	{
		if ($this->groupHandler !== null)
		{
			$this->groupHandler = new GroupHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('user_group')
			);
		}
		
		return $this->groupHandler;		
	}

	protected function assignmentHandler()
	{
		if ($this->assignmentHandler !== null)
		{
			$this->assignmentHandler = new UserHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('user_group_assignment')
			);
		}
		
		return $this->assignmentHandler;		
	}
}
