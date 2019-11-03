<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Tests\Cache\Res\Metadata\Registry;

use Eki\NRW\Component\Core\Cache\Res\Metadata\Registry\Cache;
use Eki\NRW\Common\Res\Metadata\Registry;

use Symfony\Component\Cache\Adapter\ArrayAdapter;

use PHPUnit\Framework\TestCase;

use stdClass;

class CacheTest extends TestCase
{
	/**
	* @var Cache
	*/
	private $cache;
	
	public function setUp()
	{
		$registry = new Registry();
		$registry->addFrom(
			'alias_1', 
			array(
				'classes' => array(
					'abc' => stdClass::class
				)
			)
		);
		$registry->addFrom(
			'alias_2', 
			array(
				'classes' => array(
					'def' => stdClass::class
				)
			) 
		);
		
		$this->cache = new Cache(new ArrayAdapter(), $registry);
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
		
		$objFromCache = $cache->get(99, 'alias_2.def');
		$this->assertNotNull($objFromCache);
	}

	public function testSetThenGetWithInfo()
	{
		$cache = $this->cache;
		
		$obj = new stdClass();
		$obj->id = 99;
		$obj->identifier = "IDENTIFIER";
		$cache->set($obj, 'identifier');
		
		$objFromCache = $cache->get('IDENTIFIER', 'alias_2.def');
		$this->assertNotNull($objFromCache);
		$this->assertEquals($obj, $objFromCache);
	}

	public function testClearNoInfo()
	{
		$cache = $this->cache;
		
		$obj = new stdClass();
		$obj->id = 99;
		$cache->set($obj);
		
		$objFromCache = $cache->get(99, 'alias_2.def');
		$this->assertNotNull($objFromCache);
		
		$cache->clear($objFromCache);
		$objFromCacheAfterClear = $cache->get(99, 'alias_2.def');
		$this->assertNull($objFromCacheAfterClear);
	}
}
