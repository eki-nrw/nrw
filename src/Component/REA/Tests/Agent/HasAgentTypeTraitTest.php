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

use Eki\NRW\Mdl\REA\Agent\HasAgentTypeTrait;
use Eki\NRW\Mdl\REA\Agent\AgentTypeInterface;

use PHPUnit\Framework\TestCase;

class HasAgentTypeTraitTest extends TestCase
{
	public function testHasAgentType()
	{
		$trait = $this->getMockBuilder(HasAgentTypeTrait::class)->getMockForTrait();
		$agentType = $this->getMockBuilder(AgentTypeInterface::class)
			->disableAutoload()
			->getMock()
		;
		
		$trait->setAgentType($agentType);
		$this->assertEquals($agentType, $trait->getAgentType());
	}
}
