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

use Eki\NRW\Component\Core\Tests\Cache\BaseCacheTest;

use stdClass;
use ArrayObject;

class Class1
{
	
}

class CacheTest extends BaseCacheTest
{
	/**
	* @var \Eki\NRW\Common\Res\Metadata\Metadata
	*/
	private $metadata;
	
	public function tearDown()
	{
		$this->metadata = null;
		
		parent::tearDown();
	}
	
	protected function getCache()
	{
		if (null === $this->metadata)
		{
			$this->metadata = new Metadata(
				"alias", 
				array(
					'xyz' => stdClass::class,
					'first' => Class1::class
				),
				array(
					'default_name' => 'xyz'
				)
			);
		}
		
		$cache = new Cache(new ArrayAdapter(), $this->metadata);
		
		return $cache;
	}

	protected function newObject($ref)
	{
		$class = $this->metadata->getClass($ref);

		return new $class();
	}

	/**
	* @expectedException \InvalidArgumentException
	* 
	*/
	public function testTwoRefHaveTheSameClass()
	{
		$cache = new Cache(
			new ArrayAdapter(),
			new Metadata(
				"alias", 
				array(
					'def' => stdClass::class,
					'xyz' => stdClass::class,
					'first' => Class1::class
				), 
				array()
			)
		);
	}
}
