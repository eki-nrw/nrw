<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Permission\User;

use Eki\NRW\Component\SPBase\Permission\User\Group as BaseGroup;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
trait GroupAssignmentTrait
{
	/**
	* @var \Eki\NRW\Component\SPBase\Permission\User\Group
	*/
	protected $group;
	
	/**
	* Returns user group
	* 
	* @return \Eki\NRW\Component\SPBase\Permission\User\Group
	*/
	public function getGroup()
	{
		return $this->group;
	}
}
