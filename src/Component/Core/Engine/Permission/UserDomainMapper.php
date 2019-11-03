<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Permission;

use Eki\NRW\Component\SPBase\Persistence\Permission\User\User as PSUser;
use Eki\NRW\Component\SPBase\Persistence\Permission\User\Group as PSGroup;

use Eki\NRW\Component\Base\Engine\Permission\User\User as BaseUser;
use Eki\NRW\Component\Base\Engine\Permission\User\Group as BaseGroup;

use Eki\NRW\Component\Core\Engine\Permission\User\User;
use Eki\NRW\Component\Core\Engine\Permission\User\Group;

/**
 * User Domain Mapper
 */
class UserDomainMapper
{
	/**
	* Build domain user group object from persistence user group object
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group $psGroup
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\User\Group
	*/
	public function buildDomainGroupObject(PSGroup $psGroup)
	{
		$group = new Group(array(
			'parentId' => $psGroup->parentId
		));
		
		return $group;		
	}

	/**
	* Build domain user object from persistence user object
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\User $psUser
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\User\User
	*/
	public function buildDomainUserObject(PSUser $psUser)
	{
		$user = new User(array(
			'login' => $psUser->login,
			'email' => $psUser->email,
		));
		
		return $user;		
	}
	
	/**
	* Update persistence group object by domain group object
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\Group $psGroup
	* @param \Eki\NRW\Component\Base\Engine\Permission\User\Group $group
	* 
	* @return
	*/
	public function updatePersistenceGroupObject(PSGroup $psGroup, BaseGroup $group)
	{
		//...
		
		return $psGroup;
	}

	/**
	* Update persistence user object by domain user object
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\User\User $psUser
	* @param \Eki\NRW\Component\Base\Engine\Permission\User\User $user
	* 
	* @return
	*/
	public function updatePersistenceUserObject(PSUser $psUser, BaseUser $User)
	{
		//...
		
		return $psUser;
	}
}
