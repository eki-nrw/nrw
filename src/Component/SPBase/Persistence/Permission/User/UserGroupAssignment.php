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

use Eki\NRW\Common\Res\Model\ResInterface;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class UserGroupAssignment extends GroupAssignment, ResInterface
{
	/**
	* The id of the user object 
	* 
	* @var mixed
	*/
	protected $userId;
}
