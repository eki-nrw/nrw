<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Event;

use Eki\NRW\Mdl\REA\Event\AbstractEventType;
use Eki\NRW\Mdl\REA\Event\EventTypeInterface;

use PHPUnit\Framework\TestCase;

class AbstractEventTypeTest extends TestCase
{
    public function testTypeName()
    {
    	$eventType = $this->getEventType();
    	
    	$this->assertEmpty($eventType->getEventType());
    }

	public function testIsInput()
	{
    	$eventType = $this->getEventType();
    	$this->assertNull($eventType->isInput());
    	
    	$eventType = $this->getInputEventType();
    	$this->assertTrue($eventType->isInput());

    	$eventType = $this->getOutputEventType();
    	$this->assertFalse($eventType->isInput());
	}

	public function testIsProvide()
	{
    	$eventType = $this->getEventType();
    	$this->assertNull($eventType->isProvide());

    	$eventType = $this->getProvideEventType();
    	$this->assertTrue($eventType->isProvide());

    	$eventType = $this->getReceiveEventType();
    	$this->assertFalse($eventType->isProvide());
	}
	
	private function getEventType()
	{
    	return $this->getMockBuilder(AbstractEventType::class)->getMockForAbstractClass();
	}

	private function getInputEventType()
	{
    	$eventType =  $this->getMockBuilder(AbstractEventType::class)
    		->setMethods(['isInput'])
    		->getMockForAbstractClass()
    	;
    	
    	$eventType->expects($this->any())
    		->method('isInput')
    		->will($this->returnValue(true))
    	;
    	
    	return $eventType;
	}

	private function getOutputEventType()
	{
    	$eventType =  $this->getMockBuilder(AbstractEventType::class)
    		->setMethods(['isInput'])
    		->getMockForAbstractClass()
    	;
    	
    	$eventType->expects($this->any())
    		->method('isInput')
    		->will($this->returnValue(false))
    	;
    	
    	return $eventType;
	}
	
	private function getProvideEventType()
	{
    	$eventType =  $this->getMockBuilder(AbstractEventType::class)
    		->setMethods(['isProvide'])
    		->getMockForAbstractClass()
    	;
    	
    	$eventType->expects($this->any())
    		->method('isProvide')
    		->will($this->returnValue(true))
    	;
    	
    	return $eventType;
	}
	
	private function getReceiveEventType()
	{
    	$eventType =  $this->getMockBuilder(AbstractEventType::class)
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
