<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Permission\User;

use Eki\NRW\Component\SPBase\Permission\Role\Limitation\RoleLimitation;
use Eki\NRW\Component\Core\Persistence\Permission\Role\Role;
use Eki\NRW\Component\Core\Persistence\Permission\Role\RoleAssignment;

/**
 * 
 */
class GroupRoleAssignment extends RoleAssignment
{
	/**
	* @var \Eki\NRW\Component\SPBase\Permission\User\Group
	*/
	protected $user;

	public function __construct(Group $group, Role $role, RoleLimitation $roleLimitation)
	{
		$this->group = $group;
		
		parent::__construct($role, $roleLimitation);
	}

	/**
	* Returns user group
	* 
	* @return \Eki\NRW\Component\SPBase\Permission\User\Group
	*/
	public function getGroup()
	{
		return $this->group;
	}
}
