<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Agent\Extension\Core;

use Eki\NRW\Mdl\REA\Agent\Extension\Core\Agent;
use Eki\NRW\Mdl\REA\Agent\AgentTypeInterface;

use PHPUnit\Framework\TestCase;

class AgentTest extends TestCase
{
	public function testPerson()
	{
		// Person
		$person = $this->createAgent();
		$person->setAgentType($this->createAgentType(true));
		
		$this->assertTrue($person->isPerson());
		
		// Not Person
		$animal = $this->createAgent();
		$animal->setAgentType($this->createAgentType(false));
		
		$this->assertFalse($animal->isPerson());
	}
	
	private function createAgent()
	{
    	$agent = $this->getMockBuilder(Agent::class)->getMockForAbstractClass();
    	
    	return $agent;
	}
	
	private function createAgentType($isPerson = true)
	{
		$agentType = $this->getMockBuilder(AgentTypeInterface::class)
			->setMethods(['isPersonType'])
			->getMockForAbstractClass()
		;
		
		$agentType->expects($this->any())
			->method('isPersonType')
			->will($this->returnValue($isPerson))
		;
		
		return $agentType;
	}
}
