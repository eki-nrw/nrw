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

use Eki\NRW\Mdl\REA\Resource\AbstractResourceTypeElement;
use Eki\NRW\Mdl\REA\Resource\ResourceTypeInterface;
use Eki\NRW\Common\Element\AbstractElement;
use Eki\NRW\Common\Element\ElementInterface;

use PHPUnit\Framework\TestCase;

class AbstractResourceTypeElementTest extends TestCase
{
	public function testInterfaces()
	{
		$element = $this->getMockBuilder(AbstractResourceTypeElement::class)
			->disableOriginalConstructor()
			->getMockForAbstractClass()
		;
		
		$this->assertInstanceOf(AbstractElement::class, $element);
		$this->assertInstanceOf(ElementInterface::class, $element);
	}

	public function testElementTypeSpecified()
	{
		$element = $this->getMockBuilder(AbstractResourceTypeElement::class)
			->disableOriginalConstructor()
			->getMockForAbstractClass()
		;
		
		$this->assertNotEmpty($element->getElementType());
	}	
	
	public function testResourceTypeAsContainer()
	{
		$element = $this->getMockBuilder(AbstractResourceTypeElement::class)
			->disableOriginalConstructor()
			->getMockForAbstractClass()
		;

		$agentType = $this->getMockBuilder(ResourceTypeInterface::class)->getMockForAbstractClass();
		
		$element->setResourceType($agentType);
		$this->assertEquals($agentType, $element->getContainer());		
	}
}
