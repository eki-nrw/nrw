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

use Eki\NRW\Mdl\REA\Agent\Extension\Core\OrgAgentType;

use PHPUnit\Framework\TestCase;

class OrgAgentTypeTest extends TestCase
{
	public function testAgentTypeSpecified()
	{
		$agentType = $this->createAgentType();
		
		$this->assertNotEmpty($agentType->getAgentType());
	}

	public function testOrganizationIsNotPerson()
	{
		$agentType = $this->createAgentType();
		
		$this->assertFalse($agentType->isPersonType());
	}
	
	private function createAgentType()
	{
    	$agentType = $this->getMockBuilder(OrgAgentType::class)->getMockForAbstractClass();
    	
    	return $agentType;
	}
}
