<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine;

use Eki\NRW\Component\Base\Engine\Permission\Role\Role;
use Eki\NRW\Component\Base\Engine\Permission\Role\Policy;
use Eki\NRW\Component\Base\Engine\Permission\Role\RoleAssignment;
use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation\RoleLimitation;

use Eki\NRW\Component\Base\Engine\Permission\User\UserRoleAssignment;
use Eki\NRW\Component\Base\Engine\Permission\User\GroupRoleAssignment as UserGroupRoleAssignment;

use Eki\NRW\Component\Base\Engine\Permission\User\User;
use Eki\NRW\Component\Base\Engine\Permission\User\Group as UserGroup;

use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException;
use Eki\NRW\Component\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Base\Exceptions\UnauthorizedException;

/**
 * Role Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface RoleService
{
	/**
	* Create new role of given identifier
	* 
	* @param string|int $identifier
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Policy[] $policies
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\Role
	*/
	public function createRole($identifier, array $policies = []);

	/**
	* Load role by id
	* 
	* @param string|int $roleId
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\Role
	*/
	public function loadRole($roleId);

	/**
	* Load role by identifier
	* 
	* @param string|int $identifier
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\Role
	*/
	public function loadRoleByIdentifier($identifier);
	
	/**
	* Update a role
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Role $role
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException
	* 
	* @return void
	*/
	public function updateRole(Role $role);

	/**
	* Update the given role
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Role $role
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	* 
	* @return void
	*/	
	public function deleteRole(Role $role);

	/**
	* Add the given policy to the given role
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Role $role Persisted role
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Policy $policy Non-persisted policy as arguments
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\Policy Added persisted policy
	*/
	public function addPolicy(Role $role, Policy $policy);

	/**
	* Load policy by id
	* 
	* @param string|int $policyId
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\Policy
	*/
	public function loadPolicy($policyId);

	/**
	* Update the given policy
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Policy $policy
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException
	* 
	* @return void
	*/
	public function updatePolicy(Policy $policy);

	/**
	* Remove the given policy
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Policy $policy
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	* 
	* @return void
	*/
	public function removePolicy(Policy $policy);
	
	/**
	* Assigns the given role to the given user with/without role limitation
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Role $role
	* @param \Eki\NRW\Component\Base\Engine\Permission\User\User $user
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation\RoleLimitation $roleLimitation
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\User\UserRoleAssignment
	*/
	public function assignRoleToUser(Role $role, User $user, RoleLimitation $roleLimitation = null);

	/**
	* Unassigns the given role from the given user
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Role $role
	* @param \Eki\NRW\Component\Base\Engine\Permission\User\User $user
	* 
	* @return void
	*/
	public function unassignRoleFromUser(Role $role, User $user);

	/**
	* Assigns the given role to the given user group with/without role limitation
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Role $role
	* @param \Eki\NRW\Component\Base\Engine\Permission\User\Group $userGroup
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation\RoleLimitation $roleLimitation
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\GroupRoleAssignment
	*/
	public function assignRoleToUserGroup(Role $role, UserGroup $userGroup, RoleLimitation $roleLimitation = null);

	/**
	* Unassigns the given role from the given user group
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Role $role
	* @param \Eki\NRW\Component\Base\Engine\Permission\User\Group $userGroup
	* 
	* @return void
	*/
	public function unassignRoleFromUserGroup(Role $role, UserGroup $userGroup);
	
	/**
	* Load a role assignment
	* 
	* @param mixed $roleAssignmentId
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\RoleAssignment
	*/
	public function loadRoleAssignment($roleAssignmentId);

	/**
	* Gets all role assignments of the given role
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\Role $role
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\RoleAssignment[]
	*/
	public function getRoleAssignments(Role $role);

	/**
	* Gets all role assignments of the given user
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\User\User $user
	* @param bool $inherited
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\UserRoleAssignment[]
	*/
	public function getRoleAssignmentsForUser(User $user, $inherited = false);

	/**
	* Gets all role assignments of the given user group
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\User\Group $userGroup
	* @param bool $inherited
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\GroupRoleAssignment[]
	*/
	public function getRoleAssignmentsForUserGroup(UserGroup $userGroup);
	
	/**
	* Remove the given role assignment
	* 
	* @param \Eki\NRW\Component\Base\Engine\Permission\Role\RoleAssignment $roleAssignment
	* 
	* @return void
	*/
	public function removeRoleAssignment(RoleAssignment $roleAssignment);
	
	/**
	* Returns limitation type registered with given identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\SPBase\Permission\Limitation\Type
	*/
	public function getLimitationType($identifier);
	
	/**
	* Get all limitation types of service/permission
	* 
	* @param string $service
	* @param string $permission
	* 
	* @return \Eki\NRW\Component\SPBase\Permission\Limitation\Type
	*/
    public function getLimitationTypesByServicePermission($service, $permission);
}
