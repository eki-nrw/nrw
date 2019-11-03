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

use Eki\NRW\Component\Base\Engine\Permission\User\GroupAssignment as BaseGroupAssignment;
use Eki\NRW\Component\Base\Engine\Permission\User\Group;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class GroupAssignment extends BaseGroupAssignment
{
	/**
	* 
	* @var \Eki\NRW\Component\Base\Engine\Permission\User\Group
	*/
	protected $group;
	
	public function __construct(Group $group)
	{
		$this->group = $group;
	}
	
	/**
	* Returns user group
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\User\Group
	*/
	public function getGroup()
	{
		return $this->group;
	}
}
