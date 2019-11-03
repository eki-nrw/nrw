<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Resourcing\Resource;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\BaseBaseHandlerTest;
use Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Resource\Handler;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Resourcing\Fixtures\Resource;

class HandlerTest extends BaseBaseHandlerTest
{
	private $handler;

	public function setUp()
	{
		$this->handler = $this->createBasePersistenceHandler(
			$this->createObjectManager(),
			$this->createCache(),
			$this->configMetadata(
				"resource", 
				array(
					'default' => Resource::class,
					'resource.good' => Resource::class,
					'good.best.ok' => Resource::class,
					'everything.ok' => Resource::class,
				),
				array(
					'cache_prefix' => 'resource',
					'cache_tag' => 'resource'
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
		
		$resource = $handler->create("resource.good");
		$this->assertNotNull($resource);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testCreateWrongIdentifier()
	{
		$handler = $this->handler;
		
		$resource = $handler->create("resource.wrong");
	}
	
	public function testLoad()
	{
		$handler = $this->handler;

		$resource = $handler->create("resource.good");

		$loadedResource = $handler->load($resource->getId());
		$this->assertNotNull($loadedResource);
		$this->assertSame($loadedResource->getId(), $resource->getId());
	}	
	
	public function testUpdate()
	{
		$handler = $this->handler;

		$resource = $handler->create("resource.good");
		$resource->setName("The new resource name");
		
		$handler->update($resource);
		
		$loadedResource = $handler->load($resource->getId());
		$this->assertSame("The new resource name", $loadedResource->getName(0));
	}

	public function testDelete()
	{
		$handler = $this->handler;

		$resource = $handler->create("resource.good");

		$handler->delete($resource);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteThenLoad()
	{
		$handler = $this->handler;

		$resource = $handler->create("resource.good");
		$id = $resource->getId();

		$handler->delete($resource);
		$handler->load($id);
	}
}
