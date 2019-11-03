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

use Eki\NRW\Component\SPBase\Permission\Role\RoleAssignment;

/**
 * This class represents a Limitation applied to a policy.
 */
abstract class GroupRoleAssignment extends RoleAssignment
{
	/**
	* Returns user group
	* 
	* @return \Eki\NRW\Component\SPBase\Permission\User\Group
	*/
	public function getGroup();
}
