<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Permission\User\Assignment;

use Eki\NRW\Component\SPBase\Persistence\Permission\User\User as BaseUser;
use Eki\NRW\Component\SPBase\Persistence\Permission\User\Group as BaseGroup;
use Eki\NRW\Component\SPBase\Persistence\Permission\User\UserGroupAssignment as BaseUserGroupAssignment;

use Eki\NRW\Component\Core\Persistence\Permission\User\UserGroupAssignment;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;

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
	public function createAssignment(BaseUser $user, BaseGroup $group)
	{
		$assignment = new UserGroupAssignment($user, $group);
		
		$this->updateAssignment($assignment);
		$loadedAssignment = $this->loadAssignment($assignment->getId());
		
		return $loadedAssignment;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function loadAssignment($id)
	{
		$cacheItem = $this->getCacheItem($id);	
		if ($cacheItem->isHit())
			return $cacheItem->get();

		$assignment = $this->findRes($id);		
		
		if (null === $user)
            throw new NotFoundException('UserGroupAssignment', $id);
		
		$this->setCacheItem($assignment);

		return $assignment;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function deleteAssignment(BaseUserGrouAssignment $assignment)
	{
		$this->resCache->deleteCacheItemByKey($assignment, $this->cacheKeyEx($assignment->getUser(), $assignment->getGroup()));
		$this->deleteCacheItem($assignment);
		$this->objectManager->remove($assignment);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function updateAssignment(BaseUserGrouAssignment $assignment)
	{
		if (null !== ($group = $assignment->getGroup()) and null !== ($user = $assignment->getUser()))
			$this->setCacheItemEx($assignment);

		$this->setCacheItem($assignment);
		$this->objectManager->persist($assignment);
	}

	public function findAssignment(BaseUser $user, BaseGroup $group)
	{
		$cacheKey = $this->cacheKeyEx($user, $group);
		$cacheItem = $this->resCache->getCacheItemByKey($cacheKey));
		if (!$cacheItem->isHit())
		{
			$assignment = $this->findResOneBy(
				array(
					'userId' => $user->getId(),
					'groupId' => $group->getId()
				)
			);

			if (null !== $assignment)
				$this->resCache->setCacheItemByKey($assignment, $cacheKey);
		}
		else
		{
			$assignment = $cacheItem->get();
		}

		return $assignment;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function findGroupsOfUser(BaseUser $user, $limit = null, $offset = null)
	{
		$assignments = $this->findResBy(
			array(
				'user' => $user
			),
			null,
			$limit,
			$offset
		);
		
		$groups = [];
		foreach($assignments as $assignment)
		{
			$groups[] = $assignment->getGroup();
		}
		
		return $groups;
	}
	
	private function cacheKeyEx(BaseUser $user, BaseGroup $group)
	{
		$cacheKey = 
			$this->metadata->getParameter('cache_prefix') .
			"-group-" . $group->getId() . 
			"-user-" . $user->getId()
		;
		
		return $cacheKey;
	}
}
