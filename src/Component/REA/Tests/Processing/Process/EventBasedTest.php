<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Processing\Process;

use Eki\NRW\Mdl\REA\Event\EventInterface;
use Eki\NRW\Mdl\REA\Event\EventTypeInterface;

use PHPUnit\Framework\TestCase;

class EventBasedTest extends TestCase
{
	protected $inputEventType;
	protected $outputEventType;
	
	public function setUp()
	{
		$this->inputEventType = $this->createInputEventType();
		$this->outputEventType = $this->createOutputEventType();
	}
	
	public function tearDown()
	{
		$this->inputEventType = null;
		$this->outputEventType = null;
	}
	
	protected function createInputEvent()
	{
		$event = $this->getMockBuilder(EventInterface::class)
			->setMethods(['getEventType'])
			->getMockForAbstractClass()
		;

		$event->expects($this->any())
			->method('getEventType')
			->will($this->returnCallback(function () {
				return $this->inputEventType;
			}))
		;
			
		return $event;
	}

	protected function createOutputEvent()
	{
		$event = $this->getMockBuilder(EventInterface::class)
			->setMethods(['getEventType'])
			->getMockForAbstractClass()
		;

		$event->expects($this->any())
			->method('getEventType')
			->will($this->returnCallback(function () {
				return $this->outputEventType;
			}))
		;
			
		return $event;
	}
	
	private function createInputEventType()
	{
		$eventType = $this->getMockBuilder(EventTypeInterface::class)
			->setMethods(['isInput'])
			->getMockForAbstractClass()
		;
		$eventType->expects($this->any())
			->method('isInput')
			->will($this->returnValue(true))
		;
		
		return $eventType;
	} 

	private function createOutputEventType()
	{
		$eventType = $this->getMockBuilder(EventTypeInterface::class)
			->setMethods(['isInput'])
			->getMockForAbstractClass()
		;
		$eventType->expects($this->any())
			->method('isInput')
			->will($this->returnValue(false))
		;
		
		return $eventType;
	} 
}
