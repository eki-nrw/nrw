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

use Eki\NRW\Component\SPBase\Permission\User\UserGroupAssignment as UserGroupAssignmentInterface;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class UserGroupAssignment implements UserGroupAssignmentInterface
{
	use
		ResTrait,
		GroupAssignmentTrait
	;
	
	/**
	* @var \Eki\NRW\Component\SPBase\Permission\User\User
	*/
	protected $user;

	public function __construct(User $user = null, Group $group = null)
	{
		$this->user = $user;
		$this->group = $group;
	}
	
	/**
	* Returns user
	* 
	* @return \Eki\NRW\Component\SPBase\Permission\User\User
	*/
	public function getUser()
	{
		return $this->user;		
	}
}
