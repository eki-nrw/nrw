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

use Eki\NRW\Mdl\REA\Agent\Extension\Core\Type\ProjectAgentType;

use PHPUnit\Framework\TestCase;

class ProjectAgentTypeTest extends TestCase
{
	public function testAgentTypeSpecified()
	{
		$agentType = $this->createAgentType();
		
		$this->assertSame('project', $agentType->getAgentType());
	}

	public function testProjectIsNotPerson_OfCousrse()
	{
		$agentType = $this->createAgentType();
		
		$this->assertFalse($agentType->isPersonType());
	}
	
	private function createAgentType()
	{
    	$agentType = $this->getMockBuilder(ProjectAgentType::class)->getMockForAbstractClass();
    	
    	return $agentType;
	}
}
