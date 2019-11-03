<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Event\Extension\Core;

use Eki\NRW\Mdl\REA\Event\Extension\Core\InputEventType;

use PHPUnit\Framework\TestCase;

class InputEventTypeTest extends TestCase
{
	public function testEventTypeFeatures()
	{
		$eventType = $this->createEventType();
		
		$this->assertSame('input', $eventType->getEventType());
		$this->assertTrue($eventType->isInput());
		$this->assertNull($eventType->isProvide());
	}

	private function createEventType()
	{
    	$eventType = $this->getMockBuilder(InputEventType::class)->getMockForAbstractClass();
    	
    	return $eventType;
	}
}
