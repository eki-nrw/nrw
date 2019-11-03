<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Tests\Agent;

use Eki\NRW\Component\REA\Agent\AbstractAgentType;
use Eki\NRW\Component\REA\Agent\AgentTypeInterface;

use Eki\NRW\Common\Extension\BuilderInterface;
use Eki\NRW\Common\Extension\BuildInterface;
use Eki\NRW\Common\Extension\NormalizeInterface;

use PHPUnit\Framework\TestCase;

class AbstractAgentTypeTest extends TestCase
{
	private $agentType;
	
	public function setUp()
	{
    	$this->agentType = $this->getMockBuilder(AbstractAgentType::class)->getMockForAbstractClass();
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
	
    public function testBuild()
    {
    	$agentType = $this->agentType;

    	$builder = $this->getMockBuilder(BuilderInterface::class)->getMock();
    	$contexts = array();
    	
    	// build method return void
		$this->assertNull($agentType->build($builder, $contexts));    	
	}    

    public function testBuildAgent()
    {
    	$agentType = $this->agentType;

    	$builder = $this->getMockBuilder(BuilderInterface::class)->getMock();
    	$contexts = array();
    	
    	// build method return void
		$this->assertNull($agentType->buildAgent($builder, $contexts));    	
	}    
}
