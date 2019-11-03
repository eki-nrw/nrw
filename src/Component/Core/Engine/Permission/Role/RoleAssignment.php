<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Permission\Role;

use Eki\NRW\Component\Base\Engine\Permission\Role\RoleAssignment as BaseRoleAssignment;
use Eki\NRW\Component\Base\Engine\Permission\Role\Role as BaseRole;
use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation\RoleLimitation;

/**
 */
class RoleAssignment extends BaseRoleAssignment
{
	/**
	* @var \Eki\NRW\Component\Base\Engine\Permission\Role\Role;
	*/
	protected $role;
	
	/**
	* @var \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation\RoleLimitation
	*/
	protected $roleLimitation;
	
	public function __construct(BaseRole $role, RoleLimitation $roleLimitation)
	{
		$this->role = $role;
		$this->roleLimitation = $roleLimitation;
	}
	
	/**
	* Returns role
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\Role
	*/
	public function getRole()
	{
		return $this->role;
	}

	/**
	* Returns role limitation
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation\RoleLimitation|null
	*/
    public function getRoleLimitation()
    {
		return $this->roleLimitation;
	}
}
