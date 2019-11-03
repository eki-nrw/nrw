<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Working\WorkingSubject;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\PrepareBaseHandlerTest;
use Eki\NRW\Component\Core\Persistence\Doctrine\Working\WorkingSubject\Handler;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Working\Fixtures\WorkingSubject;

class HandlerTest extends PrepareBaseHandlerTest
{
	public function setUp()
	{
		$this->handlerClass = Handler::class;
		
		$this->metadata = new Metadata(
			"def", 
			array(
				'default' => WorkingSubject::class,
				'def' => WorkingSubject::class,
				'working.def' => WorkingSubject::class,
			),
			array(
				'cache_prefix' => 'working_subject',
				'cache_tag' => 'working_subject'
			)
		);

		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
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
}
