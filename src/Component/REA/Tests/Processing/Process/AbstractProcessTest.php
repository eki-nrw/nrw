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

use Eki\NRW\Mdl\REA\Processing\Process\AbstractProcess;
use Eki\NRW\Mdl\REA\Processing\Process\ProcessInterface;

use PHPUnit\Framework\TestCase;

class AbstractProcessTest extends EventBasedTest
{
	public function setUp()
	{
		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}
	
    public function testInterfaces()
    {
    	$process = $this->createProcess();
    	
    	$this->assertInstanceOf(ProcessInterface::class, $process);
	}
	
	public function getFirstNewm()
	{
    	$process = $this->createProcess();
    	
    	$this->assertEmpty($process->getProcessName());
    	$this->assertEmpty($process->getInputEvents());
    	$this->assertNull($process->getOutputEvent());
		
	}

    public function testProcessName()
    {
    	$process = $this->createProcess();

		$process->setProcessName('process name');
		$this->assertSame('process name', $process->getProcessName());    	
	}

	public function testAddInputEvent()
	{
    	$process = $this->createProcess();
    	
    	$event_a = $this->createInputEvent();
    	$event_b = $this->createInputEvent();
    	$process->addInputEvent($event_a, 'key_a');
    	$process->addInputEvent($event_b, 'key_b');
    	
    	$this->assertEquals($event_a, $process->getInputEvent('key_a'));
    	$this->assertEquals($event_b, $process->getInputEvent('key_b'));

    	$this->assertEquals(2, sizeof($process->getInputEvents()));

    	$event_c = $this->createInputEvent();
    	$process->addInputEvent($event_a, 'key_c');

    	$this->assertEquals(3, sizeof($process->getInputEvents()));
	}

	public function testSetOutputEvent()
	{
    	$process = $this->createProcess();

    	$event = $this->createOutputEvent();
    	$process->setOutputEvent($event);
    	
    	$this->assertEquals($event, $process->getOutputEvent());
	}	
	
	private function createProcess()
	{
    	return $this->getMockBuilder(AbstractProcess::class)
    		->getMockForAbstractClass()
    	;
	}
}
