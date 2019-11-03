<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Resourcing;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\PrepareGroupHandlerTest;

use Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Handler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Resource\Handler as ResourceHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Resource\Type\Handler as ResourceTypeHandler;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Resourcing\Fixtures\Resource;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Resourcing\Fixtures\ResourceType;

class HandlerTest extends PrepareGroupHandlerTest
{
	public function setUp()
	{
		$this->handlerClass = Handler::class; 

		$this->addToRegistry("resource",
			array(
				'classes' => array(
					'default' => Resource::class,
				),
				'cache_prefix' => 'resource',
				'cache_tag' => 'resource'
			)
		);
		$this->addToRegistry("resource_type",
			array(
				'classes' => array(
					'default' => ResourceType::class,
				),
				'cache_prefix' => 'resource_type',
				'cache_tag' => 'resource_type'
			)
		);
		
		parent::setUp();
	}

	public function tearDown()
	{
		parent::tearDown();
	}
	
	public function testResourceHandler()
	{
		$handler = $this->handler;
		
		$resourceHandler = $handler->resourceHandler();
		$this->assertNotNull($resourceHandler);
		$this->assertInstanceOf(ResourceHandler::class, $resourceHandler);
	}

	public function testResourceHandlerOperations()
	{
		$handler = $this->handler;
		
		$resourceHandler = $handler->resourceHandler();
		$resource = $resourceHandler->create("default");
		
		$this->assertNotNull($resource);
		
		$id = $resource->getId();
		$loadedResource = $resourceHandler->load($id);
		
		$this->assertNotNull($loadedResource);
		$this->assertSame($resource->getId(), $loadedResource->getId());
		
		$randomName = str_shuffle("78997897979 gjjkhkh 9867678678");
		$resource->setName($randomName);
		$resourceHandler->update($resource);
		$loadedResource = $resourceHandler->load($id);
		
		$this->assertSame($randomName, $loadedResource->getName());
		
		$resourceHandler->delete($loadedResource);
	}

	public function testResourceTypeHandler()
	{
		$handler = $this->handler;
		
		$resourceTypeHandler = $handler->resourceTypeHandler();
		$this->assertNotNull($resourceTypeHandler);
		$this->assertInstanceOf(ResourceTypeHandler::class, $resourceTypeHandler);
	}

	public function testResourceTypeHandlerOperations()
	{
		$handler = $this->handler;

		$resourceTypeHandler = $handler->resourceTypeHandler();
		$resourceType = $resourceTypeHandler->create("default");
		
		$this->assertNotNull($resourceType);
		
		$id = $resourceType->getId();
		$loadedResourceType = $resourceTypeHandler->load($id);
		
		$this->assertNotNull($loadedResourceType);
		$this->assertSame($resourceType->getId(), $loadedResourceType->getId());
		
		$randomName = str_shuffle("ashdhlas 87897979 jhashkjhdsakjhdk");
		$resourceType->setName($randomName);
		$resourceTypeHandler->update($resourceType);
		$loadedResourceType = $resourceTypeHandler->load($id);
		
		$this->assertSame($randomName, $loadedResourceType->getName());
		
		$resourceTypeHandler->delete($loadedResourceType);
	}
}
