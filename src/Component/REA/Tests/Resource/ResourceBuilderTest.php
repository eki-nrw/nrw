<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Resource;

use Eki\NRW\Mdl\REA\Resource\ResourceBuilder;
use Eki\NRW\Common\Extension\AbstractBuilder;
use Eki\NRW\Common\Extension\CreateBuilderInterface;
use Eki\NRW\Mdl\REA\Resource\ResourceTypeInterface;
use Eki\NRW\Mdl\REA\Resource\ResourceFactory;

use PHPUnit\Framework\TestCase;

class ResourceBuilderTest extends TestCase
{
    public function testInterfacesAndMore()
    {
    	$builder = $this->createBuilder();
    	
    	$this->assertInstanceOf(AbstractBuilder::class, $builder);
    	$this->assertInstanceOf(CreateBuilderInterface::class, $builder);
    	$this->assertInstanceOf(ResourceFactory::class, $builder->getFactory());
	}

    public function testCreateBuilder()
    {
    	$builder = $this->createBuilder();
    	
    	$newBuilder = $builder->createBuilder(
			$this->getMockBuilder(ResourceTypeInterface::class)->getMockForAbstractClass()
    	);
    	
    	$this->assertNotNull($newBuilder);
    	$this->assertInstanceOf(get_class($builder) , $newBuilder);
	}

	private function createBuilder()
	{
		$agentType = $this->getMockBuilder(ResourceTypeInterface::class)->getMockForAbstractClass();
		$factory = $this->getMockBuilder(ResourceFactory::class)
			->disableOriginalConstructor()
			->getMock()
		;
		
		return new ResourceBuilder($agentType, $factory);
	}
}
