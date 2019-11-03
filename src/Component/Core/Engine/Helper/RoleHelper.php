<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Helper;

use Eki\NRW\Component\Base\Permission\BuildRoleInterface;
use Eki\NRW\Component\Base\Permission\RoleBuilderInterface;

/**
 *
 */
class RoleHelper
{
	protected $roleBuilder;
	
	public function createAssociationRole(AssociationInterface $association)
	{
		$agent = $association->getAgent();
		$otherAgent = $association->getOtherAgent();

		$roleBuilder = $this->roleBuilder;
		$association->buildRole($roleBuilder);
		$role = $roleBuilder->getRole();
		
		return $role;
	}
}
