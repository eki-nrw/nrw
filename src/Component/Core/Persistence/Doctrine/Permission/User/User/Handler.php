<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Permission\User\User;

use Eki\NRW\Component\SPBase\Persistence\Permission\User\User as PSUser;

use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Core\Persistence\Permission\User\User;

use Eki\NRW\Component\Base\Core\Exceptions\NotFoundException;
use Eki\NRW\Component\Base\Core\Exceptions\InvalidArgumentException;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Handler extends BaseHandler
{
	/**
	* @inheritdoc
	* 
	*/
	public function createUser(PSUser $user)
	{
		if (null !== ($loadedUser = $this->loadUserByLogin($user->__get('login'))))
			throw new InvalidArgumentException("user", "Already user that has login $login.");

		$persistUser = new User();
		$persistUser->setLogin($user->getLogin());
		$persistUser->setCredentialInfo($user->getCredentialInfo());

		$psUser = $this->updateUser($persistUser);
		$loadedUser = $this->loadUser($psUser->getId());

		return $loadedUser;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function loadUser($userId)
	{
		if (null !== ($user = $this->getObjectFromCache($userId, 'user')))
			return $user;

		$user = $this->findRes($userId);		
		if (null === $user)
            throw new NotFoundException('User', $userId);
		
		$this->setObjectToCache($user, "user");

		return $user;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function loadUserByLogin($login)
	{
		if (null !== ($user = $this->getObjectFromCache("user-login-".$user->login, 'user')))
			return $user;

		$user = $this->findResByOne(array('login'=>$login));
		if ($user !== null)
            throw new NotFoundException('User', $login);

		$this->setObjectToCache($user, "byLogin-".$login);

		return $user;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function updateUser(PSUser $user)
	{
		$this->setObjectToCache($user, "user");
		$this->setObjectToCache($user, "byLogin-".$user->login);
		$this->objectManager->persist($user);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function deleteUser(PSUser $user)
	{
		$this->clearObjectFromCache($user, "user");
		$this->clearObjectFromCache($user, "byLogin-".$user->login);
		$this->objectManager->remove($user);
	}
}
