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

use Eki\NRW\Mdl\REA\Event\Extension\Core\ReceiveEventType;

use PHPUnit\Framework\TestCase;

class ReceiveEventTypeTest extends TestCase
{
	public function testEventTypeFeatures()
	{
		$eventType = $this->createEventType();
		
		$this->assertSame('receive', $eventType->getEventType());
		$this->assertNull($eventType->isInput());
		$this->assertFalse($eventType->isProvide());
	}

	private function createEventType()
	{
    	$eventType = $this->getMockBuilder(ReceiveEventType::class)->getMockForAbstractClass();
    	
    	return $eventType;
	}
}
