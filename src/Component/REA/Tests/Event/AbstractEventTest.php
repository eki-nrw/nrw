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

use Eki\NRW\Mdl\REA\Event\AbstractEvent;
use Eki\NRW\Mdl\REA\Event\EventTypeInterface;

use Eki\NRW\Common\QuantityValue\QuantityValueInterface;

use PHPUnit\Framework\TestCase;

class AbstractEventTest extends TestCase
{
	private $event;
	
	public function setUp()
	{
    	$this->event = $this->getMockBuilder(AbstractEvent::class)->getMockForAbstractClass();
	}
	
	public function tearDown()
	{
		$this->event = null;
	}
	
    public function testEventType()
    {
    	$event = $this->event;
    	
    	$eventType = $this->getMockBuilder(EventTypeInterface::class)->getMock();
    	$event->setEventType($eventType);
    	
    	$this->assertNotNull($event->getEventType());
    }

    public function testAffectedQuantity()
    {
    	$event = $this->event;
			
		// Default location is null
		$this->assertNull($event->getAffectedQuantity());

		// Sets a location		
		$quantityValue = $this->getMockBuilder(QuantityValueInterface::class)->getMockForAbstractClass();
		$event->setAffectedQuantity($quantityValue);
		$this->assertNotNull($event->getAffectedQuantity());
		
		// Reset main location of event
		$event->setAffectedQuantity();
		$this->assertNull($event->getAffectedQuantity());
	}
}
