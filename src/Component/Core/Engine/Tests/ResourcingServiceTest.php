<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Tests;

use Eki\NRW\Component\Core\Persistence\Resourcing\Handler as ResourcingHandler;
use Eki\NRW\Component\Core\Engine\ResourcingService;

use Eki\NRW\Component\REA\Resource\ResourceTypeBuilder;
use Eki\NRW\Component\REA\Resource\ResourceBuilder;

use Eki\NRW\Component\Core\Persistence\Tests\Resourcing\Fixtures\Resource;
use Eki\NRW\Component\Core\Persistence\Tests\Resourcing\Fixtures\ResourceType;

use PHPUnit\Framework\TestCase;

class ResourcingServiceTest extends PrepareServiceTest
{
	public function setUp()
	{
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
		
		$this->serviceClass = ResourcingService::class;
		$this->handlerClass = ResourcingHandler::class;

		$this->extraArgs = [
			new ResourceTypeBuilder('null'),
			new ResourceBuilder(new ResourceType)
		];
		
		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}
	
	public function testCreateResourceType()
	{
		$service = $this->service;
		
		$resourceType = $service->createResourceType("default");
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	* 
	*/
	public function testCreateResourceTypeWithWrongIdentifier()
	{
		$service = $this->service;
		
		$resourceType = $service->createResourceType("wrong.identifier");
	}

	public function testCreateResourceTypeThenLoad()
	{
		$service = $this->service;
		
		$resourceType = $service->createResourceType("default");
		$loadedResourceType = $service->loadResourceType($resourceType->getId());
		
		$this->assertSame($loadedResourceType->getId(), $resourceType->getId());
	}
}

