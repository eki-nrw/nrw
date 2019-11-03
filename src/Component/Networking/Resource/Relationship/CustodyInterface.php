<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Resource\Relationship;

use Eki\NRW\Mdl\REA\Relationship\CustodyInterface as BaseCustodyInterface;
use Eki\NRW\Component\Base\Permission\Role\BuildRoleInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface CustodyInterface extends 
	BaseCustodyInterface,
	BuildRoleInterface
{
	// role determination
	// + role identifier
	// + default policies
	//   + defalt limation (agent type
	// Maybe use Role Builder) 
}
