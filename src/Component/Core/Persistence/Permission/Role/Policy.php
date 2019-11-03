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

use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Policy as PSPolicy;
use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role as PSRole;

/**
 */
class Policy extends PSPolicy
{
    /**
     * @var \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role
     */
    protected $role;

	/**
	* Return role object
	* 
	* @return \Eki\NRW\Component\Core\Persistence\Permission\Role\Role|null
	*/
	public function getRole()
	{
		return $this->role;
	}

	/**
	* Sets role object
	* 
	* @param \Eki\NRW\Component\Core\Persistence\Permission\Role\Role $role
	* 
	*/	
	public function setRole(Role $role)
	{
		$this->role = $role;
		$this->roleId = $role->getId();
	}
}
