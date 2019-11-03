<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Working\WorkingSubject;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\BaseBaseHandlerTest;
use Eki\NRW\Component\Core\Persistence\Doctrine\Working\WorkingSubject\Handler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Working\Subject\Handler as SubjectHandler;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Working\Fixtures\WorkingSubject;
use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Working\Fixtures\Subject;

class HandlerTest extends BaseBaseHandlerTest
{
	private $handler;

	public function setUp()
	{
		$this->handler = $this->createBasePersistenceHandler(
			$this->createObjectManager(),
			$this->createCache(),
			$this->configMetadata(
				"workingSubject", 
				array(
					'default' => WorkingSubject::class,
					'def' => WorkingSubject::class,
					'working.def' => WorkingSubject::class,
				),
				array(
					'cache_prefix' => 'working_subject',
					'cache_tag' => 'working_subject'
				)
			),
			Handler::class
		);
	}
	
	public function tearDown()
	{
		$this->handler = null;
	}

	public function testCreateWorkingSubject()
	{
		$handler = $this->handler;
		
		$ws = $handler->createWorkingSubject("def");
		$this->assertNotNull($ws);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testCreateWrongIdentifier()
	{
		$handler = $this->handler;
		
		$ws = $handler->createWorkingSubject("working_subject.wrong");
	}
	
	public function testLoadWorkingSubject()
	{
		$handler = $this->handler;

		$ws = $handler->createWorkingSubject("def");

		$loadedWS = $handler->loadWorkingSubject($ws->getId());
		$this->assertNotNull($loadedWS);
		$this->assertSame($loadedWS->getId(), $ws->getId());
	}	
	
	public function testUpdateWorkingSubject()
	{
		$handler = $this->handler;

		$ws = $handler->createWorkingSubject("def");
		$ws->setName("The new subject name");
		
		$handler->updateWorkingSubject($ws);
		
		$loadedWS = $handler->loadWorkingSubject($ws->getId());
		$this->assertSame("The new subject name", $loadedWS->getName());
	}

	public function testDeleteWorkingSubject()
	{
		$handler = $this->handler;

		$ws = $handler->createWorkingSubject("def");

		$handler->deleteWorkingSubject($ws);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteThenLoadWorkingSubject()
	{
		$handler = $this->handler;

		$ws = $handler->createWorkingSubject("def");
		$id = $ws->getId();

		$handler->deleteWorkingSubject($ws);
		$handler->loadWorkingSubject($id);
	}
	
	public function testFindWorkingSubject()
	{
		$handler = $this->handler;

		$subjectHandler = $this->createBasePersistenceHandler(
			$handler->getObjectManager(),
			$handler->getCache(),
			$this->configMetadata(
				"subject", 
				array(
					'default' => Subject::class,
					'subject' => Subject::class,
				),
				array(
					'cache_prefix' => 'subject',
					'cache_tag' => 'subject'
				)
			),
			SubjectHandler::class
		);

		$ws = $handler->createWorkingSubject("def");
		$subject = $subjectHandler->createSubject("default");
		$ws->setSubject($subject);
		$handler->updateWorkingSubject($ws);

		$findWS = $handler->findWorkingSubject("def", $subject);
		$this->assertNotNull($findWS);
		$this->assertSame($ws->getId(), $findWS->getId());
	}
}
