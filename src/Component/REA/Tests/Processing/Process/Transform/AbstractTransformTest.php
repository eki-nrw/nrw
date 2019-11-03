<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Processing\Process\Transform;

use Eki\NRW\Mdl\REA\Tests\Processing\Process\EventBasedTest;

use Eki\NRW\Mdl\REA\Processing\Process\Transform\AbstractTransform;

use PHPUnit\Framework\TestCase;

class AbstractTransformTest extends EventBasedTest
{
	public function setUp()
	{
		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}
	
	public function testFirstNew()
	{
		$transform = $this->createAbstractTransform();

		$this->assertNull($transform->getTransformName());
		$this->assertEmpty($transform->getInputEvents());
		$this->assertNull($transform->getOutputEvent());
	}

	public function testTransformName()
	{
		$transform = $this->createAbstractTransform();

		$transform->setTransformName('transform name');
		$this->assertSame('transform name', $transform->getTransformName());
	}

	public function testAddInputEvent()
	{
		$transform = $this->createAbstractTransform();
		
		$event_a = $this->createInputEvent();
		$event_b = $this->createInputEvent();
		
		$transform->addInputEvent($event_a, 'key_a');
		$transform->addInputEvent($event_b, 'key_b');
		
		$this->assertTrue($transform->hasInputEvent('key_a'));
		$this->assertTrue($transform->hasInputEvent('key_b'));
		
		$this->assertEquals($event_a, $transform->getInputEvent('key_a'));
		$this->assertEquals($event_b, $transform->getInputEvent('key_b'));
	}

    /**
     * @expectedException \InvalidArgumentException
     */
	public function testAddInputEvent_EventIsOutputNotInput()
	{
		$transform = $this->createAbstractTransform();
		
		$event = $this->createOutputEvent();
		
		$transform->addInputEvent($event, 'a_key');
	}

	public function testSetOutputEvent()
	{
		$transform = $this->createAbstractTransform();
		
		$event = $this->createOutputEvent();
		
		$transform->setOutputEvent($event);
		$this->assertEquals($event, $transform->getOutputEvent());
		
		// Reset output event
		$transform->setOutputEvent(null);
		$this->assertNull($transform->getOutputEvent());
	}

	private function createAbstractTransform()
	{
		return $this->getMockBuilder(AbstractTransform::class)
			->getMockForAbstractClass()
		;
	}
}
