<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Permission\Role;

use Eki\NRW\Component\SPBase\Persistence\Permission\Role;
use Eki\NRW\Component\SPBase\Persistence\Permission\Policy;

use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException;
use Eki\NRW\Component\Base\Exceptions\NotFoundException;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create a new role entity in a storage engine
	* 
	* @param string $identifier
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException Only a role that have $identifier
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role
	*/
	public function createRole($identifier);
	
	/**
	* Load role object
	* 
	* @param int|string $roleId
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	* 
	*/
	public function loadRole($roleId);

	/**
	* Load role by identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role
	* 
    * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException
	* 
	*/
	public function loadRoleByIdentifier($identifier);
	
	/**
	* Load all roles
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role[]
	*/
	public function loadRoles();

	/**
	* Delete given role
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role $role
	* 
	* @return void
	*/	
	public function deleteRole(Role $role);
	
	/**
	* Update a role
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role $role
	* 
	* @return void
	*/
	public function updateRole(Role $role);

    /**
     * Loads role assignment for specified assignment ID.
     *
     * @param mixed $roleAssignmentId
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException If role assignment is not found
     *
     * @return \Eki\NRW\Component\SPBase\Persistence\Permission\RoleRoleAssignment
     */
    public function loadRoleAssignment($roleAssignmentId);

    /**
     * Loads roles assignments Role.
     *
     * Role Assignments with same roleId and limitationIdentifier will be merged together into one.
     *
     * @param mixed $roleId
     *
     * @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\RoleAssignment[]
     */
    public function loadRoleAssignmentsByRoleId($roleId);

    /**
     * Loads roles assignments to a user.
     *
     * Role Assignments with same roleId and limitationIdentifier will be merged together into one.
     *
     * @param mixed $userId 
     * @param bool $inherit If true also return inherited role assignments from user groups.
     *
     * @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\RoleAssignment[]
     */
    public function loadRoleAssignmentsByUserId($userId, $inherit = false);

    /**
     * Loads roles assignments to a user/group.
     *
     * Role Assignments with same roleId and limitationIdentifier will be merged together into one.
     *
     * @param mixed $userGroupId
     * @param bool $inherit If true also return inherited role assignments from user groups.
     *
     * @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\RoleAssignment[]
     */
    public function loadRoleAssignmentsByGroupId($userGroupId, $inherit = false);

	/**
	* Create a new policy entity in a storage engine
	* 
	* @param string $service
	* @param string $function
	* @param array $limitations
	* 
	* @return \Eki\NRW\Component\SPBase\Permission\Role\Policy
	*/
	public function createPolicy($service, $function array $limitations = []);

    /**
     * Adds a policy to a role.
     *
     * @param string|int $roleId
     * @param \Eki\NRW\Component\Permission\Role\Policy $policy
     *
     * @return \Eki\NRW\Component\Permission\Role\Role\Policy
     *
     * @todo Throw on invalid Role Id?
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException If $policy->limitation is empty (null, empty string/array..)
     */
    public function addPolicy($roleId, Policy $policy);

	/**
	* Load policy object
	* 
	* @param int|string $policyId
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Policy
	*/
	public function loadPolicy($policyId);

	/**
	* Delete given policy
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Policy $policy
	* 
	* @return void
	*/	
	public function deletePolicy(Policy $policy);
	
	/**
	* Update a policy
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Policy $policy
	* 
	* @return void
	*/
	public function updatePolicy(Policy $policy);
	
//	public function assignRoleToUser($userId, $roleId, array $limitations = array());
//	public function unAssignRoleFromUser($userId, $roleId);
	
//	public function assignRoleToUserGroup($userGroupId, $roleId, array $limitations = array());
//	public function unAssignRoleFromUserGroup($userGroupId, $roleId);
	
	public function removeRoleAssignment($roleAssignmentId);
}
