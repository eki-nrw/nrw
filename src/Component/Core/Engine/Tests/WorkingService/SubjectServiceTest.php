<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Tests\Working;

use Eki\NRW\Component\Core\Persistence\Working\Subject\Handler as SubjectHandler;
use Eki\NRW\Component\Core\Engine\Working\SubjectService;

use Eki\NRW\Mdl\Working\Subject\BaseObjectBuilder;
use Eki\NRW\Mdl\Working\Subject\Director;

use Eki\NRW\Mdl\Working\Subject\CallbackInterface;
use Eki\NRW\Mdl\Working\Subject\ValidatorInterface;
use Eki\NRW\Mdl\Working\Subject\ImportorInterface;

use Eki\NRW\Component\Core\Persistence\Tests\Working\Fixtures\Subject;
use Eki\NRW\Component\Core\Persistence\Tests\Working\Fixtures\Plan;
use Eki\NRW\Component\Core\Persistence\Tests\Working\Fixtures\PlanItem;
use Eki\NRW\Component\Core\Persistence\Tests\Working\Fixtures\Activity;
use Eki\NRW\Component\Core\Persistence\Tests\Working\Fixtures\Execution;

use Eki\NRW\Component\Core\Engine\Tests\PrepareServiceTest;

use PHPUnit\Framework\TestCase;

use stdClass;

class SubjectServiceTest extends PrepareServiceTest
{
	public function setUp()
	{
		$this->addToRegistry("subject",
			array(
				'classes' => array(
					'default' => Subject::class,
					'subject' => Subject::class,
					'plan' => Plan::class,
					'plan_item' => PlanItem::class,
					'activity' => Activity::class,
					'execution' => Execution::class,
				),
				'cache_prefix' => 'agent',
				'cache_tag' => 'agent'
			)
		);
		
		$this->serviceClass = SubjectService::class;
		$this->handlerClass = SubjectHandler::class;

		$this->extraArgs = [
			$this->createSubjectDirector($this->getSettigs())		    
		];
		
		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}
	
	public function testCreateSubject()
	{
		$service = $this->service;
		
		$subject = $service->createSubject("default");
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	* 
	*/
	public function testCreateSubjectWithWrongIdentifier()
	{
		$service = $this->service;
		
		$subject = $service->createSubject("wrong.identifier");
	}

	public function testLoadSubject()
	{
		$service = $this->service;
		
		$subject = $service->createSubject("default");
		$loadedSubject = $service->loadSubject($subject->getId());
		
		$this->assertSame($loadedSubject->getId(), $subject->getId());
	}

	public function testUpdateSubject()
	{
		$service = $this->service;
		
		$subject = $service->createSubject("default");
		$subject->setName("The Agent Type");
		
		$service->updateSubject($subject);
		$loadedSubject = $service->loadSubject($subject->getId());
		
		$this->assertSame("The Agent Type", $loadedSubject->getName());
	}

	public function testDeleteSubject()
	{
		$service = $this->service;
		
		$subject = $service->createSubject("default");
		
		$service->deleteSubject($subject);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteSubjectThenLoad()
	{
		$service = $this->service;
		
		$subject = $service->createSubject("default");
		
		$service->deleteSubject($subject);
		$service->loadSubject($subject->getId());
	}

	private function createCallback($type)
	{
		$callback = $this->getMockBuilder(CallbackInterface::class)
			->setMethods(['getCallbackType'])
			->getMockForAbstractClass()
		;
		
		$callback
			->expects($this->any())
			->method('getCallbackType')
			->will($this->returnCallback(function() use ($type) {
				return $type;
			} ))
		;
		
		return $callback;
	}

	private function getSettigs()
	{
		$validator = $this->getMockBuilder(ValidatorInterface::class)->getMock();
		$importor = $this->getMockBuilder(ImportorInterface::class)->getMock();
		
		return array(
			'subjectBuilderClass' => BaseObjectBuilder::class,
			'subjectDirectorClass' => Director::class,
			'subject_types' => array(
				'default' => array(
                    'subject' => Subject::class,
                    'callback' => $this->createCallback('default'),
                    'importor' => $importor,
                    'validator' => $validator,
				),
				'subject' => array(
                    'subject' => Subject::class,
                    'callback' => $this->createCallback('subject'),
                    'importor' => $importor,
                    'validator' => $validator,
				),
				'plan' => array(
                    'subject' => Plan::class,
                    'callback' => $this->createCallback('plan'),
                    'importor' => $importor,
                    'validator' => $validator,
				),
				'plan_item' => array(
                    'subject' => PlanItem::class,
                    'callback' => $this->createCallback('plan_item'),
                    'importor' => $importor,
                    'validator' => $validator,
				),
				'activity' => array(
                    'subject' => Activity::class,
                    'callback' => $this->createCallback('activity'),
                    'importor' => $importor,
                    'validator' => $validator,
				),
				'execution' => array(
                    'subject' => Execution::class,
                    'callback' => $this->createCallback('execution'),
                    'importor' => $importor,
                    'validator' => $validator,
				),
			)
		);
	}

	private function createSubjectDirector(array $settings)
	{
		$registries = [];
		foreach($settings['subject_types'] as $type => $subject)
		{
			$registry = $subject;
			$registry['type'] = $type;
			$registries[] = $registry;
		}
		
		$subjectBuilderClass = $settings['subjectBuilderClass'];
		$subjectDirectorClass = $settings['subjectDirectorClass'];
		
		$director = new $subjectDirectorClass($registries, $subjectBuilderClass);
		
		return $director;
	}
}

