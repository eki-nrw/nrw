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

use Eki\NRW\Mdl\REA\Agent\AbstractAgentTypeElement;
use Eki\NRW\Mdl\REA\Agent\AgentTypeInterface;
use Eki\NRW\Common\Element\AbstractElement;
use Eki\NRW\Common\Element\ElementInterface;

use PHPUnit\Framework\TestCase;

class AbstractAgentTypeElementTest extends TestCase
{
	public function testInterfaces()
	{
		$element = $this->getMockBuilder(AbstractAgentTypeElement::class)
			->disableOriginalConstructor()
			->getMockForAbstractClass()
		;
		
		$this->assertInstanceOf(AbstractElement::class, $element);
		$this->assertInstanceOf(ElementInterface::class, $element);
	}

	public function testElementTypeSpecified()
	{
		$element = $this->getMockBuilder(AbstractAgentTypeElement::class)
			->disableOriginalConstructor()
			->getMockForAbstractClass()
		;
		
		$this->assertNotEmpty($element->getElementType());
	}	
	
	public function testAgentTypeAsContainer()
	{
		$element = $this->getMockBuilder(AbstractAgentTypeElement::class)
			->disableOriginalConstructor()
			->getMockForAbstractClass()
		;

		$agentType = $this->getMockBuilder(AgentTypeInterface::class)->getMockForAbstractClass();
		
		$element->setAgentType($agentType);
		$this->assertEquals($agentType, $element->getContainer());		
	}
}
