<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Processing\Processor;

use Eki\NRW\Mdl\REA\Processing\Processor\AbstractProcessor;

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

	public function testAutomate()
	{
		$processor = $this->createProcessor();
		
		$subject = $this->createSubject("subject_for_automate");
		
		$processor->automate($subject);
	}

    /**
     * @expectedException \LogicException
     */
	public function testAutomate_AfterStarted()
	{
		$processor = $this->createProcessor();
		
		$subject = $this->createSubject("subject_for_automate_after_start");
		
		$processor->start($subject);
		$processor->automate($subject);
	}

	public function testStart()
	{
		$processor = $this->createProcessor();
		
		$subject = $this->createSubject("subject_a");
		
		$processor->start($subject);
		
		$this->assertTrue($processor->isStarted($subject));
		$this->assertTrue($processor->isProcessing($subject));
	}

    /**
     * @expectedException \LogicException
     */
	public function testStart_Twice()
	{
		$processor = $this->createProcessor();
		
		$subject = $this->createSubject("subject_x");
		
		$processor->start($subject);
		$processor->start($subject);
	}

	public function testStartThenStop()
	{
		$processor = $this->createProcessor();
		
		$subject = $this->createSubject("subject_b");
		
		$processor->start($subject);
		$this->assertTrue($processor->isStarted($subject));
		$this->assertTrue($processor->isProcessing($subject));
		
		usleep(10);
		
		$processor->stop($subject);
		$this->assertTrue($processor->isStopped($subject));
		$this->assertFalse($processor->isProcessing($subject));
	}

    /**
     * @expectedException \LogicException
     */
	public function testStop_Twice()
	{
		$processor = $this->createProcessor();
		
		$subject = $this->createSubject("subject_for_stop_twice");
		
		$processor->start($subject);
		$processor->stop($subject);
		$processor->stop($subject);
	}

    /**
     * @expectedException \LogicException
     */
	public function testPause_Twice()
	{
		$processor = $this->createProcessor();
		
		$subject = $this->createSubject("subject_for_pause_twice");
		
		$processor->start($subject);
		
		usleep(1);
		
		$processor->pause($subject);
		$processor->pause($subject);
	}

    /**
     * @expectedException \LogicException
     */
	public function testResume_Twice()
	{
		$processor = $this->createProcessor();
		
		$subject = $this->createSubject("subject_for_resume_twice");
		
		$processor->start($subject);
		
		usleep(1);
		
		$processor->pause($subject);

		usleep(1);
		
		$processor->resume($subject);
		$processor->resume($subject);
	}

	public function testStartThenPauseThenResumeThenStop()
	{
		$processor = $this->createProcessor();
		
		$subject = $this->createSubject("subject_b");
		
		$processor->start($subject);
		$this->assertTrue($processor->isStarted($subject));
		$this->assertTrue($processor->isProcessing($subject));
		
		usleep(10);
		
		$processor->pause($subject);
		$this->assertTrue($processor->isPaused($subject));
		$this->assertFalse($processor->isProcessing($subject));

		// Pause don't affect started status
		$this->assertTrue($processor->isStarted($subject));

		usleep(10);
		
		$processor->resume($subject);
		$this->assertFalse($processor->isPaused($subject));
		$this->assertTrue($processor->isProcessing($subject));

		// Resume don't affect started status
		$this->assertTrue($processor->isStarted($subject));
		
		usleep(10);
		
		$processor->stop($subject);
		$this->assertTrue($processor->isStopped($subject));
		$this->assertFalse($processor->isProcessing($subject));

		// Stop don't affect started status
		$this->assertTrue($processor->isStarted($subject));
	}
	
	private function createProcessor()
	{
		$processor = $this->createAnProcessor(AbstractProcessor::class);
    	
    	$processor->expects($this->any())
    		->method('support')
    		->will($this->returnCallback(function ($subject) {
    			return ($subject instanceof stdClass);
    		}))
    	;

    	$processor->expects($this->any())
    		->method('acting')
    		->will($this->returnCallback(function ($action, $subject) {
    			if ($this->printOn)
    				print "Acting ". $action . " for subject " . get_class($subject) . "\n";
    		}))
    	;

    	return $processor;
	}
	
	private function createSubject($subjectName)
	{
		return $this->createAnSubject($subjectName, stdClass::class);
	}
}
