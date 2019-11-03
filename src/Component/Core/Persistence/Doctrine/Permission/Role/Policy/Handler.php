<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Permission\Role\Policy;

use Eki\NRW\Component\SPBase\Persistence\BaseHandler;

use Eki\NRW\Component\SPBase\Permission\Role\Policy as PSPolicy;
use Eki\NRW\Component\Core\Persistence\Permission\Role\Policy;

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
	public function createPolicy($service, $function, array $limitations)
	{
		$psPolicy = new Policy();
		
		$psPolicy->service = $service;
		$pspolicy->function = $function;
		$psPolicy->limitations = $limtations;
		
		$this->updatePolicy($policy);
		
		return $policy;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function loadPolicy($id)
	{
		$cacheItem = $this->getCacheItem($id);	
		if ($cacheItem->isHit())
			return $cacheItem->get();

		if (null !== ($policy = $this->getObjectFromCache($id)))
			return $policy;

		$policy = $this->findRes($id);		
		if (null === $policy)
            throw new NotFoundException('Policy', $id);
		
		$this->setObjectToCache($policy);

		return $policy;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function deletePolicy(PSPolicy $policy)
	{
		$this->clearObjectFromCache($policy);
		$this->objectManager->remove($policy);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function updatePolicy(BasePolicy $policy)
	{
		$this->setObjectToCache($policy);
		$this->objectManager->persist($policy);
	}
}
