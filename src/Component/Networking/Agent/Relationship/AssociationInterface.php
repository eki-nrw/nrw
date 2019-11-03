<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Agent\Relationship;

use Eki\NRW\Component\REA\Relationship\AssociationInterface as BaseAssociationInterface;
//use Eki\NRW\Component\Base\Permission\Role\BuildRoleInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface AssociationInterface extends 
	BaseAssociationInterface //,
	//BuildRoleInterface
{
	// role determination
	// + role identifier
	// + default policies
	//   + defalt limation (agent type
	// Maybe use Role Builder) 
}
