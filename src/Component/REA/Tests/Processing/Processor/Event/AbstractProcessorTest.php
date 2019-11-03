<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Processing\Processor\Event;

use Eki\NRW\Mdl\REA\Tests\Processing\Processor\AbstractProcessorBasedTest;
use Eki\NRW\Mdl\REA\Processing\Processor\Event\AbstractProcessor;
use Eki\NRW\Mdl\REA\Event\EventInterface;
use Eki\NRW\Mdl\REA\Relationship\StockflowInterface;
use Eki\NRW\Mdl\REA\Resource\ResourceInterface;
use Eki\NRW\Common\QuantityValue\QuantityValue;

use PHPUnit\Framework\TestCase;

use stdClass;

class AbstractProcessorTest extends AbstractProcessorBasedTest
{
	public function setUp()
	{
		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}

	public function testStart()
	{
		$processor = $this->createProcessor();
		
		$event = $this->createEvent("event_a");
		
		$processor->start($event);
		$this->assertTrue($processor->isStarted($event));
	}

	public function testStartThenStop()
	{
		$processor = $this->createProcessor();
		
		$event = $this->createEvent("event_b");
		
		$processor->start($event);
		$this->assertTrue($processor->isStarted($event));
		$processor->stop($event);
		$this->assertTrue($processor->isStarted($event));
		$this->assertTrue($processor->isStopped($event));
	}

	public function testStartThenPauseThenResumeThenStop()
	{
		$processor = $this->createProcessor();
		
		$event = $this->createEvent("event_c");
		
		$processor->start($event);
		$this->assertTrue($processor->isStarted($event));
		$this->assertTrue($processor->isProcessing($event));
		
		$processor->pause($event);
		$this->assertTrue($processor->isStarted($event));
		$this->assertTrue($processor->isPaused($event));
		$this->assertFalse($processor->isProcessing($event));
		
		$processor->resume($event);
		$this->assertTrue($processor->isStarted($event));
		$this->assertFalse($processor->isPaused($event));
		$this->assertTrue($processor->isProcessing($event));
		
		$processor->stop($event);
		$this->assertTrue($processor->isStarted($event));
		$this->assertTrue($processor->isStopped($event));
		$this->assertFalse($processor->isProcessing($event));
	}

	private function createProcessor()
	{
		$processor = $this->createAnProcessor(AbstractProcessor::class, array('getStockflow'));

		$processor->expects($this->any())
			->method('getStockflow')
			->will($this->returnCallback(function () {
				return $this->createStockflow();
			}))
		;

    	return $processor;
	}
	
	private function createEvent($eventName)
	{
		$event = $this->createAnSubject($eventName, EventInterface::class, array('getAffectedQuantity'));
		
		$event->expects($this->any())
			->method('getAffectedQuantity')
			->will($this->returnCallback(function () {
				return new QuantityValue(100, 'm');
			}))
		;
		
		return $event;
	}
	
	private function createStockflow()
	{
		$stockflow = $this->getMockBuilder(StockflowInterface::class)
			->setMethods(['getResource'])
			->getMockForAbstractClass()
		;
		
		$stockflow->expects($this->any())
			->method('getResource')
			->will($this->returnCallback(function () {
				return $this->createResource();
			}))
		;
		
		return $stockflow;
	}
	
	private function createResource()
	{
		$resource = $this->getMockBuilder(ResourceInterface::class)
			->getMockForAbstractClass()
		;

		return $resource;		
	}
}
