<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Working;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\BaseGroupHandlerTest;

use Eki\NRW\Component\Core\Persistence\Doctrine\Working\Handler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Working\Subject\Handler as SubjectHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Working\WorkingSubject\Handler as WorkingSubjectHandler;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Working\Fixtures\Subject;
use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Working\Fixtures\WorkingSubject;

class HandlerTest extends BaseGroupHandlerTest
{
	private $handler;

	public function setUp()
	{
		$this->addToRegistry("subject",
			array(
				'classes' => array(
					'default' => Subject::class,
					'subject' => Subject::class,
				),
				'cache_prefix' => 'subject',
				'cache_tag' => 'subject'
			)
		);
		$this->addToRegistry("working_subject",
			array(
				'classes' => array(
					'default' => WorkingSubject::class,
				),
				'cache_prefix' => 'working_subject',
				'cache_tag' => 'working_subject'
			)
		);

		$this->handler = $this->createGroupPersistenceHandler(
			$this->createObjectManager(),
			$this->createCache(),
			$this->getMetadatas(),
			Handler::class
		);
	}

	public function tearDown()
	{
		$this->handler = null;
	}
	
	public function testSubjectHandler()
	{
		$handler = $this->handler;
		
		$subjectHandler = $handler->subjectHandler();
		$this->assertNotNull($subjectHandler);
		$this->assertInstanceOf(SubjectHandler::class, $subjectHandler);
	}

	public function testWorkingSubjectHandler()
	{
		$handler = $this->handler;

		$workingSubjectHandler = $handler->workingSubjectHandler();
		$this->assertNotNull($workingSubjectHandler);
		$this->assertInstanceOf(WorkingSubjectHandler::class, $workingSubjectHandler);
	}
}
