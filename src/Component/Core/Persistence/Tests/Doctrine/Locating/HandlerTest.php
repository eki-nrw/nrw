<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Locating;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\BaseBaseHandlerTest;
use Eki\NRW\Component\Core\Persistence\Doctrine\Locating\Handler;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Locating\Fixtures\Location;

class HandlerTest extends BaseBaseHandlerTest
{
	private $handler;
	
	public function setUp()
	{
		$this->handler = $this->createBasePersistenceHandler(
			$this->createObjectManager(),
			$this->createCache(),
			$this->configMetadata(
				"location", 
				array(
					'default' => Location::class,
					'def' => Location::class,
				),
				array(
					'cache_prefix' => 'location',
					'cache_tag' => 'location-tag'
				)
			),
			Handler::class
		);
	}
	
	public function tearDown()
	{
		$this->handler = null;
	}
	
	public function testCreate()
	{
		$handler = $this->handler;

		$location = $handler->create("def");
		$this->assertNotNull($location);
	}
	
	public function testLoad()
	{
		$handler = $this->handler;
		$location = $handler->create("def");

		$loadedLocation = $handler->load($location->getId());
		$this->assertNotNull($loadedLocation);
		$this->assertEquals($loadedLocation->getId(), $location->getId());
	}	
	
	public function testUpdate()
	{
		$handler = $this->handler;

		$location = $handler->create("def");
		$location->setLocationName("locarion_name");
		$location->setLocationType("location_type");
		
		$handler->update($location);
	}

	public function testDelete()
	{
		$handler = $this->handler;
		$location = $handler->create("def");
		
		$handler->delete($location);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteThenLoad()
	{
		$handler = $this->handler;
		$location = $handler->create("def");
		
		$id = $location->getId();
		$handler->delete($location);
		$handler->load($id);
	}
}
