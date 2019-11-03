<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Permission\User;

use Eki\NRW\Component\SPBase\Persistence\Permission\User\User;
use Eki\NRW\Component\SPBase\Persistence\Permission\User\Group;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create a user
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\User
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\User\User
	*/
	public function createUser(User $user);

	/**
	* Load user by id
	* 
	* @param mixed $userId
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\User\User
	*/	
	public function loadUser($userId);
	
	/**
	* Load user by login
	* 
	* @param mixed $userId
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\User\User
	*/	
	public function loadUserByLogin($login);

	/**
	* Update user
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\User $user
	* 
	* @return void
	*/	
	public function updateUser(User $user);
	
	/**
	* Delete user
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\User
	* 
	* @return void
	* 
	* @throws
	*/	
	public function deleteUser(User $user);

	/**
	* Assigns a user to a user group
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\User $user
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group $group
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\User\GroupAssignment
	*/	
	public function assignUserToGroup(User $user, Group $group);

	/**
	* Unassigns a user from a user group
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\User $user
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group $group
	* 
	* @return void
	*/	
	public function unassignUserFromGroup(User $user, Group $group);

	/**
	* Gets user groups of a user
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\User $user
	* @param int $limit
	* @param int $offset
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group[]
	*/
	public function getGroupsOfUser(User $user, $limit = null, $offset = null);

	/**
	* Gets sub/child groups of a group
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group $group
	* @param int $limit
	* @param int $offset
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group[]
	*/
	public function getSubGroups(Group $group, $limit = null, $offset = null);
	
	/**
	* Create a user group
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group|null $parentGroup Null if top level user group
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group
	*/
	public function createGroup(Group $parentGroup = null);

	/**
	* Load a user group
	* 
	* @param mixed $groupId
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group
	*/	
	public function loadGroup($groupId);
	
	/**
	* Delete a persisted group
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group $group
	* 
	* @return void
	* 
	* @throws
	*/
	public function deleteGroup(Group $group);
	
	/**
	* Update a modified user group
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group $group
	* 
	* @return void
	* 
	* @throws
	*/
	public function updateGroup(Group $group);

	/**
	* Move a user group new parent user group
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group $group
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group $newParent
	* 
	* @return void
	*/
    public function moveGroup(Group $group, Group $newParent);
}
