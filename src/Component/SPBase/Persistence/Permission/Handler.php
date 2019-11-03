<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Permission;

/**
 * Interface of permission handler
 *  
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
interface Handler
{
	/**
	* Returns user handler
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\User\Handler
	*/
	public function userHandler();

	/**
	* Returns role handler
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Handler
	*/
	public function roleHandler();

	/**
	* Assigns the role to the user
	* 
	* @param string|int $userId
	* @param string|int $roleId
	* @param array|null $limitations
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\RoleAssignment
	*/	
	public function assignRoleToUser($userId, $roleId, array $limitations = null);
	
	/**
	* Unassign the user from the role
	* 
	* @param string|int $userId
	* @param string|int $roleId
	* 
	* @return void
	* 
	* @throws
	*/
	public function unAssignRoleFromUser($userId, $roleId);
	
	/**
	* Assigns the role to the user group
	* 
	* @param string|int $userGroupId
	* @param string|int $roleId
	* @param array|null $limitations
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\RoleAssignment
	*/	
	public function assignRoleToUserGroup($userGroupId, $roleId, array $limitations = null);
	
	/**
	* Unassign the user group from the role
	* 
	* @param string|int $userId
	* @param string|int $roleId
	* 
	* @return void
	* 
	* @throws
	*/
	public function unAssignRoleFromUserGroup($userGroupId, $roleId);

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
    public function loadRoleAssignmentsByGroupId($groupId, $inherit = false);
}
