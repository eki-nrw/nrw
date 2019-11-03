<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Resourcing\Resource\Type;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\BaseBaseHandlerTest;
use Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Resource\Type\Handler;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Resourcing\Fixtures\ResourceType;

class HandlerTest extends BaseBaseHandlerTest
{
	private $handler;

	public function setUp()
	{
		$this->handler = $this->createBasePersistenceHandler(
			$this->createObjectManager(),
			$this->createCache(),
			$this->configMetadata(
				"resource_type", 
				array(
					'default' => ResourceType::class,
					'resource_type.good' => ResourceType::class,
					'good_type.best.ok' => ResourceType::class,
					'everything.ok' => ResourceType::class,
				),
				array(
					'cache_prefix' => 'resource_type',
					'cache_tag' => 'resource_type'
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
		
		$resourceType = $handler->create("resource_type.good");
		$this->assertNotNull($resourceType);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testCreateWrongIdentifier()
	{
		$handler = $this->handler;
		
		$resource = $handler->create("resource_type.wrong");
	}
	
	public function testLoad()
	{
		$handler = $this->handler;

		$resourceType = $handler->create("resource_type.good");

		$loadedResourceType = $handler->load($resourceType->getId());
		$this->assertNotNull($loadedResourceType);
		$this->assertSame($loadedResourceType->getId(), $resourceType->getId());
	}	
	
	public function testUpdate()
	{
		$handler = $this->handler;

		$resourceType = $handler->create("resource_type.good");
		$resourceType->setName("The new resource type name");
		
		$handler->update($resourceType);
		
		$loadedResourceType = $handler->load($resourceType->getId());
		$this->assertSame("The new resource type name", $loadedResourceType->getName());
	}

	public function testDelete()
	{
		$handler = $this->handler;

		$resourceType = $handler->create("resource_type.good");

		$handler->delete($resourceType);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteThenLoad()
	{
		$handler = $this->handler;

		$resourceType = $handler->create("resource_type.good");
		$id = $resourceType->getId();

		$handler->delete($resourceType);
		$handler->load($id);
	}
}
