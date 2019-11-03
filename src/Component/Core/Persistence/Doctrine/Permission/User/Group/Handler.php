<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Permission\User\Group;

use Eki\NRW\Component\SPBase\Persistence\Permission\User\Group as BaseGroup;

use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Core\Persistence\Permission\User\Group;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Handler extends BaseHandler
{
	/**
	* @inheritdoc
	* 
	*/
	public function createGroup(BaseGroup $parentGroup = null)
	{
		if ($parentGroup !== null and null === $this->loadGroup($parentGroup->getId()))
            throw new NotFoundException('Group', $parentGroup->getId());
		
		$group = new Group();
		if ($parentGroup !== null)
			$group->setParent($parentGroup);

		$this->updageGroup($group);
		$loadedGroup = $this->loadGroup($group->getId());
		
		return $loadedGroup;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function loadGroup($groupId)
	{
		$cacheItem = $this->getCacheItem($groupId);	
		if ($cacheItem->isHit())
			return $cacheItem->get();

		$user = $this->findRes($groupId);		
		
		if (null === $user)
            throw new NotFoundException('Group', $groupId);
		
		$this->setCacheItem($group);

		return $group;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function deleteGroup(BaseGroup $group)
	{
		$this->deleteCacheItem($group);
		$this->objectManager->remove($group);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function updateGroup(BaseGroup $group)
	{
		$this->setCacheItem($group);
		$this->objectManager->persist($group);
	}

	/**
	* @inheritdoc
	* 
	*/
    public function moveGroup(BaseGroup $group, BaseGroup $newParent)
    {
    	$newLoadedParent = $this->loadGroup($newParent->getId());
    	if ($newLoadedParent === null)
            throw new NotFoundException('Group', $newParent->getId());
    	
		$parent = $group->getParent();
		if (null !== $parent and $parent->getId() === $newLoadedParent->getId())
			throw new InvalidArgumentException("newParent", "New parent is current parent.");
		
		$group->setParent($newParent);
		
		$this->updateGroup($group);
	}
}
