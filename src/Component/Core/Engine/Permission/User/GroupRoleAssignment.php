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

use Eki\NRW\Component\Base\Permission\User\GroupRoleAssignment as GroupRoleAssignmentInterface;
use Eki\NRW\Component\Base\Permission\Role\Limitation\RoleLimitation;
use Eki\NRW\Component\Core\Permission\Role\Role;
use Eki\NRW\Component\Core\Permission\Role\RoleAssignment;

/**
 * This class represents a Limitation applied to
 */
class GroupRoleAssignment extends RoleAssignment implements GroupRoleAssignmentInterface
{
	use
		GroupAssignmentTrait
	;
	
	public function __construct(Group $group, Role $role, RoleLimitation $roleLimitation)
	{
		$this->group = $group;
		
		parent::__construct($role, $roleLimitation);
	}
}
