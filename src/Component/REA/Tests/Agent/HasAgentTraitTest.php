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

use Eki\NRW\Mdl\REA\Agent\HasAgentTrait;
use Eki\NRW\Mdl\REA\Agent\AgentInterface;

use PHPUnit\Framework\TestCase;

class HasAgentTraitTest extends TestCase
{
	public function testHasAgent()
	{
		$trait = $this->getMockBuilder(HasAgentTrait::class)->getMockForTrait();
		$agent = $this->getMockBuilder(AgentInterface::class)
			->disableAutoload()
			->getMock()
		;
		
		$trait->setAgent($agent);
		$this->assertEquals($agent, $trait->getAgent());
	}
}
