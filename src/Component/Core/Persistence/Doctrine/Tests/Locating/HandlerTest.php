<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Locating;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\PrepareBaseHandlerTest;
use Eki\NRW\Component\Core\Persistence\Doctrine\Locating\Handler;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Common\Res\Factory\FactoryInterface;
use Eki\NRW\Common\Res\Factory\Factory;
use Eki\NRW\Component\Locating\Location\Location;

use \stdClass;

class HandlerTest extends PrepareBaseHandlerTest
{
	private $handler;
	
	public function setUp()
	{
		parent::setUp();
		
		$metadata = new Metadata(
			"location", 
			array(
				'default' => Location::class
			),
			array(
				'cache_prefix' => 'location',
				'cache_tag' => 'location-tag'
			)
		);
		
		$this->handler = new Handler(
			$this->objectManager, 
			$this->cache, 
			$metadata,
			$this->createFactory()
		);
	}
	
	public function tearDown()
	{
		$this->handler = null;
		
		parent::tearDown();
	}
	
	public function testCreateAddress()
	{
		$location = $this->handler->create(
			"location_name", 
			"head_quarter", 
			array(
				'type' => 'address'
			)
		);
	}
	
	public function testLoad()
	{
		$id = 99;
		$location = new Location("location_name");
		$location->setId($id);
		$this->handler->update($location);

		$loadedLocation = $this->handler->load($id);
		$this->assertNotNull($loadedLocation);
		$this->assertEquals($loadedLocation->getId(), $id);
	}	
	
	public function testUpdate()
	{
		$id = 99;
		$location = new Location("location_name");
		$location->setId($id);
		
		$this->handler->update($location);
	}

	public function testDelete()
	{
		$id = 99;
		$location = new Location("location_name");
		$location->setId($id);
		
		$this->handler->update($location);
		$this->handler->delete($location);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteThenLoad()
	{
		$id = 99;
		$location = new Location("location_name");
		$location->setId($id);
		
		$this->handler->update($location);
		$this->handler->delete($location);
		
		$loadedLocation = $this->handler->load($id);
	}
	
	private function createFactory()
	{
		$factory = new Factory(array(
			'default' => Location::class,
			'address' => Location::class,
			'gc' => Location::class,
		));
		
		return $factory;		
	}
}
