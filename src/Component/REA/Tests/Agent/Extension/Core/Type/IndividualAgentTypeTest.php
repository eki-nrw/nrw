<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Agent\Extension\Core\Type;

use Eki\NRW\Mdl\REA\Agent\Extension\Core\Type\IndividualAgentType;

use PHPUnit\Framework\TestCase;

class IndividualAgentTypeTest extends TestCase
{
	public function testAgentTypeSpecified()
	{
		$agentType = $this->createAgentType();
		
		$this->assertSame('individual', $agentType->getAgentType());
	}

	public function testIndividualIsPerson()
	{
		$agentType = $this->createAgentType();
		
		$this->assertTrue($agentType->isPersonType());
	}
	
	private function createAgentType()
	{
    	$agentType = $this->getMockBuilder(IndividualAgentType::class)->getMockForAbstractClass();
    	
    	return $agentType;
	}
}
