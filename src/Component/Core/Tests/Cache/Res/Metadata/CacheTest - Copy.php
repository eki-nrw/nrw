<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Tests\Cache\Res\Metadata;

use Eki\NRW\Component\Core\Cache\Res\Metadata\Cache;
use Eki\NRW\Common\Res\Metadata\Metadata;

use Symfony\Component\Cache\Adapter\ArrayAdapter;

use PHPUnit\Framework\TestCase;

use stdClass;
use ArrayObject;

class CacheTest // extends TestCase
{
	/**
	* @var Cache
	*/
	private $cache;
	
	public function setUp()
	{
		$this->cache = new Cache(
			new ArrayAdapter(),
			new Metadata(
				"alias", 
				array(
					'def' => stdClass::class
				), 
				array()
			)
		);
	}
	
	public function tearDown()
	{
		$this->cache = null;
	}
	
	public function testSetThenGetNoInfo()
	{
		$cache = $this->cache;
		
		$obj = new stdClass();
		$obj->id = 99;
		$cache->set($obj);
		
		$objFromCache = $cache->get(99, 'def');
		$this->assertNotNull($objFromCache);
	}

	public function testSetThenGetWithInfo()
	{
		$cache = $this->cache;
		
		$obj = new stdClass();
		$obj->id = 99;
		$obj->identifier = "IDENTIFIER";
		$cache->set($obj, 'identifier');
		
		$objFromCache = $cache->get('IDENTIFIER', 'def');
		$this->assertNotNull($objFromCache);
		$this->assertEquals($obj, $objFromCache);
	}

	public function testClearNoInfo()
	{
		$cache = $this->cache;
		
		$obj = new stdClass();
		$obj->id = 99;
		$cache->set($obj);
		
		$objFromCache = $cache->get(99, 'def');
		$this->assertNotNull($objFromCache);
		
		$cache->clear($objFromCache);
		$objFromCacheAfterClear = $cache->get(99, 'def');
		$this->assertNull($objFromCacheAfterClear);
	}

	public function testSetThenGetForArrayObject()
	{
		$cache = new Cache(
			new ArrayAdapter(),
			new Metadata(
				"alias", 
				array(
					'arrayObject' => ArrayObject::class
				), 
				array()
			)
		);

		$obj1 = new stdClass();
		$obj1->id = 101;
		$obj2 = new stdClass();
		$obj2->id = 102;
		$obj3 = new stdClass();
		$obj3->id = 103;
		
		$arrayObj = new ArrayObject(array($obj1, $obj2, $obj3));
		$cache->set($arrayObj, "arr");
		
		$objectsFromCache = $cache->get('arr', 'arrayObject');
		$this->assertNotNull($objectsFromCache);
		$this->assertSame(3, $objectsFromCache->count());
	}
}
