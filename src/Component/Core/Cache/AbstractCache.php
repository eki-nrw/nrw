<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Cache;

use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Cache\Adapter\AdapterInterface as SymfonyCache;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
abstract class AbstractCache implements CacheInterface
{
	/**
	* @var SymfonyCache
	*/
	protected $cache;
	
	/**
	* Constructor 
	* 
	* @param \Symfony\Component\Cache\Adapter\AdapterInterface $cache
	* 
	*/
	public function __construct(SymfonyCache $cache)
	{
		$this->cache = $cache;
	}
	
	/**
	* Set object to cache
	* 
	* @param object $obj
	* @param string $info Default is self::INFO_DEFAULT
	* 
	* @return void
	*/
	public function set($obj, $info = null)
	{
		if (null === $info)
			$info = $this->getDefaultInfo();
			
		$cacheItem = $this->cache->getItem($this->cacheKeyToSet($obj, $info));
		$cacheItem->set($obj);
		$cacheItem->tag([$this->cacheTagToSet($obj, $info)]);
		$this->cache->save($cacheItem);
	}
	
	/**
	* Returns key to set a cache for the given item
	* 
	* @param object $obj
	* @param string $info
	* 
	* @return string
	*/
	protected function cacheKeyToSet($obj, $info)
	{
		$prefix = "";
		if (null !== ($classRef = $this->getClassRef(get_class($obj))))
			$prefix = $this->getCachePrefixFromObjectRef($classRef) . "-";
		
echo "cacheKeyToSet: prefix=".$prefix."    "."objectInfo=".$this->getObjectInfo($obj, $info)."\n";
		
		return $prefix . $this->getObjectInfo($obj, $info);
	}
	
	/**
	* Returns tag cache
	* 
	* @param object $obj
	* @param string $info
	* 
	* @return string
	*/
	protected function cacheTagToSet($obj, $info)
	{
		$prefix = "";
		if (null !== ($classRef = $this->getClassRef(get_class($obj))))
			$prefix = $this->getTagPrefixFromObjectRef($classRef) . "-";

		return $prefix . $this->getObjectInfo($obj, $info);
	}

	/**
	* Returns cache key to get
	* 
	* @param string $objectInfo
	* @param string $ref
	* 
	* @return string
	*/
	protected function cacheKeyToGet($objectInfo, $ref)
	{
		if (null === $ref)
		{
echo "cacheKeyToGet.objectInfo=".$objectInfo."\n";			
			return $objectInfo;			
		}
		else
		{
			return
				$this->getCachePrefixFromObjectRef($ref) . 
				"-" .
				$objectInfo
			;
		}
	}
	
	abstract protected function getCachePrefixFromObjectRef($ref);
	abstract protected function getTagPrefixFromObjectRef($ref);
	
	protected function getClassRef($class)
	{
		return null;
	}
	
	/**
	* Get object info
	* Object info is a string that combines one or many information of the object.
	* 
	* @param object $obj
	* @param string|null $info Null if default
	* 
	* @return string The combined string of the information pieces on the object
	*/
	protected function getObjectInfo($obj, $info = null)
	{
		if (null === $info)
			$info = $this->getDefaultInfo();
		
		$propertyAccessor = PropertyAccess::createPropertyAccessor();
		$objInfo = "";
		
		foreach(explode("+", $info) as $inf)
		{
			$rs = $propertyAccessor->isReadable($obj, $inf) ? $propertyAccessor->getValue($obj, $inf) : $inf;
			$objInfo = (!empty($objInfo) ? $objInfo . "+" : "") . $rs;	
		}
		
		return $objInfo;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function get($objectInfo, $ref = null)
	{
		if (null === $ref)
			$ref = $this->getDefaultRef();
			
		$cacheItem = $this->cache->getItem($this->cacheKeyToGet($objectInfo, $ref));
		if ($cacheItem->isHit())
		{
			return $cacheItem->get();
		}
	}

	/**
	* @inheritdoc
	* 
	*/
	public function clear($obj, $info = null)
	{
		if (null === $info)
			$info = $this->getDefaultInfo();
			
		if ($this->cache instanceof TagAwareAdapterInterface)
			$this->cache->invalideTags([$this->cacheTag($obj, $info)]);
		else
			$this->cache->deleteItem($this->cacheKeyToSet($obj, $info));
	}

	protected function getDefaultInfo()
	{
		throw new \Exception("It must implement this method " . __METHOD__);
	}
	
	protected function getDefaultRef()
	{
		return null;
	}
}
