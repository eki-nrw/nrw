<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine;

use Eki\NRW\Component\Core\Cache\CacheInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;

/**
 * Implmentation of Abstract Handler for Persistence 
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
abstract class AbstractHandler
{
	/**
	* @var \Doctrine\Common\Persistence\ObjectManager
	*/
	protected $objectManager;

	/**
	* @var \Symfony\Component\Cache\Adapter\AdapterInterface
	*/
	protected $cache;
	
	/**
	* @var \Eki\NRW\Component\Core\Cache\CacheInterface
	*/
	protected $resCache;
	
	/**
	* Constructor
	* 
	* @param ObjectManager $objectManager
	* @param Cache $cache
	* 
	*/
	public function __construct(ObjectManager $objectManager, Cache $cache)
	{
		$this->objectManager = $objectManager;
		$this->cache = $cache;
	}

	/**
	* Get object from cache
	* 
	* @param string $objInfo
	* @param string $ref
	* 
	* @return object|null
	*/
	protected function getObjectFromCache($objInfo, $ref = "id")
	{
		return $this->resCache->get($objInfo, $ref);
	}
	
	/**
	* Set object to cache
	* 
	* @param object $obj
	* @param string $info
	* 
	* @return void
	*/
	protected function setObjectToCache($obj, $info = "id")
	{
		$this->resCache->set($obj, $info);
	}

	/**
	* Clear cache of object
	* 
	* @param object $obj
	* @param string $info
	* 
	* @return void
	*/
	protected function clearObjectFromCache($obj, $info = "id")
	{
		$this->resCache->clear($obj, $info);
	}
}
