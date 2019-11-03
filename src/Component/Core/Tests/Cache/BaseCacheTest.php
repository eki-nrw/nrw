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

use Symfony\Component\PropertyAccess\PropertyAccess;

use PHPUnit\Framework\TestCase;

use stdClass;
use ArrayObject;

class BaseCacheTest extends TestCase
{
	/**
	* @var \Eki\NRW\Component\Core\Cache\CacheInterface
	*/
	protected $cache;
	
	protected $propertyAccessor;
	
	public function setUp()
	{
		$this->cache = $this->getCache();
		$this->propertyAccessor = PropertyAccess::createPropertyAccessor();
	}
	
	public function tearDown()
	{
		$this->cache = null;
		$this->propertyAccessor = null;
	}
	
	protected function getCache()
	{
		return null;
	}
	
	protected function createTestObj(array $properties, $ref = null)
	{
		$obj = $this->newObject($ref);
		
		if ($obj instanceof stdClass)
		{
			foreach($properties as $propName => $propValue)
			{
				$obj->$propName = $propValue;
			}
		}
		else
		{
			foreach($properties as $propName => $propValue)
			{
				$this->propertyAccessor->setValue($obj, $propName, $propValue);
			}
		}
		
		return $obj;
	}
	
	protected function newObject($ref)
	{
		return null;
	}
	
	protected function setThenGetNoInfo()
	{
		$cache = $this->cache;
		
		$obj = $this->createTestObj(array(
			'id' => 99
		));
		
		$cache->set($obj);
		
		$objFromCache = $cache->get(99);
		$this->assertNotNull($objFromCache);
		$this->assertEquals($obj, $objFromCache);
	}

	protected function setThenGetWithInfo()
	{
		$cache = $this->cache;

		$obj = $this->createTestObj(array(
			'id' => 99,
			'identifier' => "IDENTIFIER",
		));
		
		$cache->set($obj, 'identifier');
		
		$objFromCache = $cache->get('IDENTIFIER');
		$this->assertNotNull($objFromCache);
		$this->assertEquals($obj, $objFromCache);
	}

	protected function setThenGetWithInfoComplex()
	{
		$cache = $this->cache;

		$obj1 = $this->createTestObj(array(
			'id' => 99,
			'identifier' => "IDENTIFIER",
		));
		
		$cache->set($obj1, 'id+identifier');

		$obj2 = $this->createTestObj(array(
			'id' => 199,
			'identifier' => "IDENTIFIER",
		));

		$cache->set($obj2, 'id+identifier');
		
		$objFromCache = $cache->get('99+IDENTIFIER');
		$this->assertNotNull($objFromCache);
		$this->assertEquals($obj1, $objFromCache);

		$objFromCache = $cache->get('199+IDENTIFIER');
		$this->assertNotNull($objFromCache);
		$this->assertEquals($obj2, $objFromCache);
	}

	protected function clearNoInfo()
	{
		$cache = $this->cache;

		$obj = $this->createTestObj(array(
			'id' => 99
		));
		
		$cache->set($obj);
		
		$objFromCache = $cache->get(99);
		$this->assertNotNull($objFromCache);
		
		$cache->clear($objFromCache);
		$objFromCacheAfterClear = $cache->get(99);
		$this->assertNull($objFromCacheAfterClear);
	}
	
	public function testSetThenGetNoInfo()
	{
		$this->setThenGetNoInfo();
	}

	public function testSetThenGetWithInfo()
	{
		$this->setThenGetWithInfo();
	}

	public function testSetThenGetWithInfoComplex()
	{
		$this->setThenGetWithInfoComplex();
	}

	public function testClearNoInfo()
	{
		$this->clearNoInfo();
	}
}

