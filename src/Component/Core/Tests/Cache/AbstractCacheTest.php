<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Tests\Cache;

use Eki\NRW\Component\Core\Cache\AbstractCache;

use Symfony\Component\Cache\Adapter\ArrayAdapter;

use stdClass;
use ArrayObject;

class AbstractCacheTest extends BaseCacheTest
{
	protected function getCache()
	{
		$cache = $this->getMockBuilder(AbstractCache::class)
			->setMethods([
				'getCachePrefixFromObjectRef', 
				'getTagPrefixFromObjectRef', 
				'getClassRef',
				'getDefaultInfo',
				'getDefaultRef'
			])
			->setConstructorArgs([new ArrayAdapter])
			->getMockForAbstractClass()
		;

		$cache->expects($this->any())
			->method('getCachePrefixFromObjectRef')
			->will($this->returnCallback(function ($ref) {
				return $ref . "-" . "cache";
			}))
		;

		$cache->expects($this->any())
			->method('getTagPrefixFromObjectRef')
			->will($this->returnCallback(function ($ref) {
				return $ref . "-" . "tag";
			}))
		;

		$cache->expects($this->any())
			->method('getClassRef')
			->will($this->returnCallback(function ($class) {
				return $class;
			}))
		;

		$cache->expects($this->any())
			->method('getDefaultInfo')
			->will($this->returnValue("id"))
		;

		$cache->expects($this->any())
			->method('getDefaultRef')
			->will($this->returnValue(stdClass::class))
		;
		
		return $cache;
	}
	
	protected function newObject($ref)
	{
		return new stdClass;
	}

	public function testDummy()
	{
		
	}

	public function testSetThenGetForArrayObject()
	{
		$cache = $this->getCache();

		$obj1 = new stdClass();
		$obj1->id = 101;
		$obj2 = new stdClass();
		$obj2->id = 102;
		$obj3 = new stdClass();
		$obj3->id = 103;
		
		$arrayObj = new ArrayObject(array($obj1, $obj2, $obj3));
		$cache->set($arrayObj, "arr");
		
		$objectsFromCache = $cache->get('arr', ArrayObject::class);
		$this->assertNotNull($objectsFromCache);
		$this->assertSame(3, $objectsFromCache->count());
		foreach($objectsFromCache->getIterator() as $obj)
		{
			$this->assertTrue(in_array($obj, array($obj1, $obj2, $obj3)));
		}
	}
}
