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

use Eki\NRW\Mdl\REA\Agent\Extension\Core\PersonAgentType;

use PHPUnit\Framework\TestCase;

class PersonAgentTypeTest extends TestCase
{
	public function testAgentTypeSpecified()
	{
		$agentType = $this->createAgentType();
		
		$this->assertNotEmpty($agentType->getAgentType());
	}

	public function testPersonIsPerson()
	{
		$agentType = $this->createAgentType();
		
		$this->assertTrue($agentType->isPersonType());
	}
	
	private function createAgentType()
	{
    	$agentType = $this->getMockBuilder(PersonAgentType::class)->getMockForAbstractClass();
    	
    	return $agentType;
	}
}
