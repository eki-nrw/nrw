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

use Eki\NRW\Mdl\REA\Resource\AbstractResourceType;
use Eki\NRW\Mdl\REA\Resource\ResourceTypeInterface;

use Eki\NRW\Common\Extension\BuilderInterface;
use Eki\NRW\Common\Extension\BuildInterface;
use Eki\NRW\Common\Extension\NormalizeInterface;

use PHPUnit\Framework\TestCase;

class AbstractResourceTypeTest extends TestCase
{
	private $agentType;
	
	public function setUp()
	{
    	$this->agentType = $this->getMockBuilder(AbstractResourceType::class)->getMockForAbstractClass();
	}
	
	public function tearDown()
	{
		$this->agentType = null;
	}
	
	public function testInterfaces()
	{
    	$agentType = $this->agentType;

		$this->assertInstanceOf(BuildInterface::class, $agentType);
		$this->assertInstanceOf(NormalizeInterface::class, $agentType);
	}
	
    public function testName()
    {
    	$agentType = $this->agentType;
    	
    	$this->assertEmpty($agentType->getName());
    	
    	$agentType->setName('agent type name');
    	
    	$this->assertSame($agentType->getName(), 'agent type name');
    }
    
    public function testBuild()
    {
    	$agentType = $this->agentType;

    	$builder = $this->getMockBuilder(BuilderInterface::class)->getMock();
    	$contexts = array();
    	
    	// build method return void
		$this->assertNull($agentType->build($builder, $contexts));    	
	}    

    public function testBuildResource()
    {
    	$agentType = $this->agentType;

    	$builder = $this->getMockBuilder(BuilderInterface::class)->getMock();
    	$contexts = array();
    	
    	// build method return void
		$this->assertNull($agentType->buildResource($builder, $contexts));    	
	}    
}
