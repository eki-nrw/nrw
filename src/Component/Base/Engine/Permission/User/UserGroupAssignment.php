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

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
abstract class UserGroupAssignment extends GroupAssignment
{
	/**
	* Returns user
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\User\User
	*/
	public function getUser();
}
