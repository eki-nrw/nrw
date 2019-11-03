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

use Eki\NRW\Component\Base\Engine\Permission\User\UserGroupAssignment as UserGroupAssignmentInterface;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class UserGroupAssignment extends GroupAssignment implements UserGroupAssignmentInterface
{
	/**
	* @var \Eki\NRW\Component\Base\Engine\Permission\User\User
	*/
	protected $user;

	public function __construct(User $user, Group $group)
	{
		$this->user = $user;
		
		parent::__construct($group);
	}
	
	/**
	* Returns user
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\User\User
	*/
	public function getUser()
	{
		return $this->user;		
	}
}
