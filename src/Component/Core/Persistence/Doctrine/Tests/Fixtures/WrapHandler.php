<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Fixtures;

use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;

class WrapHandler extends BaseHandler
{
	public function ___getCacheItem($id)
	{
		return $this->getCacheItem($id);
	}
	
	public function ___setCacheItem($res, $cacheKey = null)
	{
		$this->setCacheItem($res, $cacheKey);
	}
	
	public function ___deleteCacheItem($res)
	{
		$this->deleteCacheItem($res);
	}

	public function addRes($res, $ref = null)
	{
		$ref = $ref !== null ? $ref : 'default';
		//$this->getRepository($ref)->add($res);
		$this->objectManager->persist($res);
	}

	public function ___findRes($id, $ref = null)
	{
		return $this->findRes($id, $ref);
	}

	public function ___findResBy(array $criteria, $ref = null)
	{
		return $this->findResBy($criteria, $ref);
	}

	public function ___findResOneBy(array $criteria, $ref = null)
	{
		return $this->findResOneBy($criteria, $ref);
	}
}

