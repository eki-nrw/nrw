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

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
interface GroupAssignment
{
	/**
	* Returns user group
	* 
	* @return \Eki\NRW\Component\SPBase\Permission\User\Group
	*/
	public function getGroup();
}
