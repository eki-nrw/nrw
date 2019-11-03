<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Agent;

use Eki\NRW\Mdl\REA\Agent\AgentTypeBuilder;
use Eki\NRW\Common\Extension\AbstractBuilder;
use Eki\NRW\Common\Extension\CreateBuilderInterface;
use Eki\NRW\Common\Res\Factory\OneClassFactory as AgentFactory;

use PHPUnit\Framework\TestCase;

class AgentTypeBuilderTest extends TestCase
{
    public function testInterfacesAndMore()
    {
    	$builder = $this->createBuilder();
    	
    	$this->assertInstanceOf(AbstractBuilder::class, $builder);
    	$this->assertInstanceOf(CreateBuilderInterface::class, $builder);
	}

    public function testCreateBuilder()
    {
    	$builder = $this->createBuilder();
    	$newBuilder = $builder->createBuilder('an_other_type_string');
    	
    	$this->assertNotNull($newBuilder);
    	$this->assertInstanceOf(get_class($builder) , $newBuilder);
	}

	private function createBuilder()
	{
		$factory = $this->getMockBuilder(AgentFactory::class)
			->disableOriginalConstructor()
			->getMock()
		;
		
		return new AgentTypeBuilder('a_type_string', $factory);
	}
}
