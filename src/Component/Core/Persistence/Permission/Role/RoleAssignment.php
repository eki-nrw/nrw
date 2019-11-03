<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Permission\Role;

use Eki\NRW\Component\SPBase\Persistence\Permission\Role\RoleAssignment as PSRoleAssignment;
use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role as PSRole;

use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Limitation\RoleLimitation;

/**
 */
class RoleAssignment extends PSRoleAssignment
{
	/**
	* @var \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role;
	*/
	protected $role;
	
	/**
	* @var \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Limitation\RoleLimitation
	*/
	protected $roleLimitation;
	
	public function __construct(Role $role, RoleLimitation $roleLimitation)
	{
		$this->role = $role;
		$this->roleLimitation = $roleLimitation;
	}
	
	/**
	* Returns role
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role
	*/
	public function getRole()
	{
		return $this->role;
	}

	/**
	* Returns role limitation
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Limitation\RoleLimitation|null
	*/
    public function getRoleLimitation()
    {
		return $this->roleLimitation;
	}
}
