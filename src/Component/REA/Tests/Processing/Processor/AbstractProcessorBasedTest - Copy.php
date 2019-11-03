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

use Eki\NRW\Mdl\REA\Processing\Processor\ProcessorInterface;

use PHPUnit\Framework\TestCase;

use stdClass;

class AbstractProcessorBasedTest extends TestCase
{
	protected $subjects;
	protected $printOn;
	
	public function setUp()
	{
		$this->subjects = [];
		$this->printOn = false;
	}
	
	public function tearDown()
	{
		foreach($this->subjects as $key => $info)
		{
			$info['subject'] = null;
		}
		$this->subjects = null;
	}

	protected function createAnProcessor($classname, array $methods = [])
	{
    	$processor = $this->getMockBuilder($classname)
    		->setMethods(array_merge(
    			['supportAction', 'checkSubjectStatus', 'dispatch'],
    			$methods
    		))
    		->getMockForAbstractClass()
    	;
		
    	$processor->expects($this->any())
    		->method('supportAction')
    		->will($this->returnCallback(function ($subject, $action) {
    			return true;
    		}))
    	;

    	$processor->expects($this->any())
    		->method('checkSubjectStatus')
    		->will($this->returnCallback(function ($subject, $status) {
    			return $subject->checkStatus($status);
    		}))
    	;
    	
    	$processor->expects($this->any())
    		->method('setSubjectStatus')
    		->will($this->returnCallback(function ($subject, $status, $statusValue) {
				foreach($this->subjects as $key => $info)
				{
					if ($info['subject'] === $subject)
					{
						$this->subjects[$key]['status'][$status] = $statusValue;
					}
				}
    		}))
    	;
    	
    	$processor->expects($this->any())
    		->method('dispatch')
    		->will($this->returnCallback(function ($subject, $action) {
				if ($action === 'start')
				{
					foreach($this->subjects as $key => $info)
					{
						if ($info['subject'] === $subject)
						{
							$this->subjects[$key]['status']['started'] = true;
							$this->subjects[$key]['status']['processing'] = true;
						}
					}
				}    			
				else if ($action === 'stop')
				{
					foreach($this->subjects as $key => $info)
					{
						if ($info['subject'] === $subject)
						{
							$this->subjects[$key]['status']['stopped'] = true;
							$this->subjects[$key]['status']['processing'] = false;
						}
					}
				}    			
				else if ($action === 'pause')
				{
					foreach($this->subjects as $key => $info)
					{
						if ($info['subject'] === $subject)
						{
							$this->subjects[$key]['status']['paused'] = true;
							$this->subjects[$key]['status']['processing'] = false;
						}
					}
				}    			
				else if ($action === 'resume')
				{
					foreach($this->subjects as $key => $info)
					{
						if ($info['subject'] === $subject)
						{
							$this->subjects[$key]['status']['paused'] = false;
							$this->subjects[$key]['status']['processing'] = true;
						}
					}
				}    			
    			
	  			if ($this->printOn)
	    			print "Dispatch event " . $action. ' for subject ' . get_class($subject) . "\n";
    		}))
    	;
    	
    	return $processor;
	}
	
	protected function createAnSubject($subjectName, $classname, array $methods = [])
	{
		$subject = $this->getMockBuilder($classname)
			->setMethods(array_merge(
				['checkStatus'],
				$methods
			))
			->getMockForAbstractClass()
		;

		$this->subjects[$subjectName] = array(
			'subject' => $subject,
			'status' => array(
				'processing' => false,
				'started' => false,
				'stopped' => false,
				'paused' => false,
			)
		);
		
		$_subjectName = $subjectName;
		
    	$subject->expects($this->any())
    		->method('checkStatus')
    		->will($this->returnCallback(function ($status) use ($_subjectName) {
				return $this->subjects[$_subjectName]['status'][$status];
    		}))
    	;

		return $subject;
	}
}
