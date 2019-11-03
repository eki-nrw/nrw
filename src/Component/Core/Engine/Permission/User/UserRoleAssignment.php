<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Permission\User;

use Eki\NRW\Component\Core\Engine\Permission\Role\RoleAssignment;
use Eki\NRW\Component\Base\Permission\User\UserRoleAssignment as UserRoleAssignmentInterface;
use Eki\NRW\Component\Base\Permission\Role\Limitation\RoleLimitation;
use Eki\NRW\Component\Core\Permission\Role\Role;

/**
 * 
 */
class UserRoleAssignment extends RoleAssignment implements UserRoleAssignmentInterface
{
	/**
	* @var \Eki\NRW\Component\Base\Permission\User\User
	*/
	protected $user;
	
	public function __construct(User $user, Role $role, RoleLimitation $roleLimitation)
	{
		$this->user = $user;

		parent::__construct($role, $roleLimitation);
	}
	
	/**
	* Returns user
	* 
	* @return \Eki\NRW\Component\Base\Permission\User\User
	*/
	public function getUser()
	{
		return $this->user;		
	}
}
