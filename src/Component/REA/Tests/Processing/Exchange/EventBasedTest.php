<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Processing\Exchange;

use Eki\NRW\Mdl\REA\Event\EventInterface;
use Eki\NRW\Mdl\REA\Event\EventTypeInterface;

use PHPUnit\Framework\TestCase;

class EventBasedTest extends TestCase
{
	protected $provideEventType;
	protected $receiveEventType;
	
	public function setUp()
	{
		$this->provideEventType = $this->createProvideEventType();
		$this->receiveEventType = $this->createReceiveEventType();
	}
	
	public function tearDown()
	{
		$this->provideEventType = null;
		$this->receiveEventType = null;
	}
	
	protected function createProvideEvent()
	{
		$event = $this->getMockBuilder(EventInterface::class)
			->setMethods(['getEventType'])
			->getMockForAbstractClass()
		;

		$event->expects($this->any())
			->method('getEventType')
			->will($this->returnCallback(function () {
				return $this->provideEventType;
			}))
		;
			
		return $event;
	}

	protected function createReceiveEvent()
	{
		$event = $this->getMockBuilder(EventInterface::class)
			->setMethods(['getEventType'])
			->getMockForAbstractClass()
		;

		$event->expects($this->any())
			->method('getEventType')
			->will($this->returnCallback(function () {
				return $this->receiveEventType;
			}))
		;
			
		return $event;
	}
	
	private function createProvideEventType()
	{
		$eventType = $this->getMockBuilder(EventTypeInterface::class)
			->setMethods(['isProvide'])
			->getMockForAbstractClass()
		;
		$eventType->expects($this->any())
			->method('isProvide')
			->will($this->returnValue(true))
		;
		
		return $eventType;
	} 

	private function createReceiveEventType()
	{
		$eventType = $this->getMockBuilder(EventTypeInterface::class)
			->setMethods(['isProvide'])
			->getMockForAbstractClass()
		;
		$eventType->expects($this->any())
			->method('isProvide')
			->will($this->returnValue(false))
		;
		
		return $eventType;
	} 
}
