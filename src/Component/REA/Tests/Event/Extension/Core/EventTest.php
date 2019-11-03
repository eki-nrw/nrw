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

use Eki\NRW\Mdl\REA\Event\Extension\Core\Event;
use Eki\NRW\Mdl\REA\Event\EventTypeInterface;

use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
	public function testInputEvent()
	{
		$eventType = $this->createEventType(true);
		$event = $this->createEvent($eventType);

		$this->assertTrue($event->isInput());		
	}

	public function testOutputEvent()
	{
		$eventType = $this->createEventType(false);
		$event = $this->createEvent($eventType);

		$this->assertFalse($event->isInput());		
	}
	
	public function testProvideEvent()
	{
		$eventType = $this->createEventType(null, true);
		$event = $this->createEvent($eventType);

		$this->assertTrue($event->isProvide());		
	}

	public function testReceiveEvent()
	{
		$eventType = $this->createEventType(null, false);
		$event = $this->createEvent($eventType);

		$this->assertFalse($event->isProvide());		
	}
	
	private function createEvent(EventTypeInterface $eventType)
	{
    	$event = $this->getMockBuilder(Event::class)
    		->setMethods(['isInput', 'isProvide'])
    		->setConstructorArgs(array($eventType))
    		->getMockForAbstractClass()
    	;
    	
    	$event->expects($this->any())
    		->method('isInput')
    		->will($this->returnCallback(function () use($event) {
    			$eventType = $event->getEventType();
    			if (null !== $eventType)
    				return $eventType->isInput();
    		}))
    	;

    	$event->expects($this->any())
    		->method('isProvide')
    		->will($this->returnCallback(function () use ($event) {
    			$eventType = $event->getEventType();
    			if (null !== $eventType)
    				return $eventType->isProvide();
    		}))
    	;
    	
    	return $event;
	}
	
	private function createEventType($isInput = null, $isProvide = null)
	{
		$eventType = $this->getMockBuilder(EventTypeInterface::class)
			->setMethods(['isInput', 'isProvide'])
			->getMockForAbstractClass()
		;
		
		$eventType->expects($this->any())
			->method('isInput')
			->will($this->returnValue($isInput))
		;

		$eventType->expects($this->any())
			->method('isProvide')
			->will($this->returnValue($isProvide))
		;
		
		return $eventType;
	}
}
