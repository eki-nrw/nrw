<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Permission\User;

use Eki\NRW\Component\Base\Engine\Permission\Role\RoleAssignment;

/**
 */
abstract class UserRoleAssignment extends RoleAssignment
{
	/**
	* Returns user
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\User\User
	*/
	public function getUser();
}
