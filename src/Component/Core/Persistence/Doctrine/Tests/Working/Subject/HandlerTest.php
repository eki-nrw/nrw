<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Working\Subject;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\PrepareBaseHandlerTest;
use Eki\NRW\Component\Core\Persistence\Doctrine\Working\Subject\Handler;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Working\Fixtures\Subject;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Working\Fixtures\Plan;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Working\Fixtures\PlanItem;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Working\Fixtures\Activity;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Working\Fixtures\Execution;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

class HandlerTest extends PrepareBaseHandlerTest
{
	public function setUp()
	{
		$this->handlerClass = Handler::class;
		
		$this->metadata = new Metadata(
			"subject", 
			array(
				'default' => Subject::class,
				'subject' => Subject::class,
				'plan' => Plan::class,
				'plan_item' => PlanItem::class,
				'activity' => Activity::class,
				'execution' => Execution::class,
			),
			array(
				'cache_prefix' => 'subject',
				'cache_tag' => 'subject'
			)
		);

		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}

	public function testCreateSubject()
	{
		$handler = $this->handler;
		
		$subject = $handler->createSubject("subject");
		$this->assertNotNull($subject);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testCreateWrongIdentifier()
	{
		$handler = $this->handler;
		
		$subject = $handler->createSubject("subject.wrong");
	}
	
	public function testLoadSubject()
	{
		$handler = $this->handler;

		$subject = $handler->createSubject("subject");

		$loadedSubject = $handler->loadSubject($subject->getId());
		$this->assertNotNull($loadedSubject);
		$this->assertSame($loadedSubject->getId(), $subject->getId());
	}	
	
	public function testUpdateSubject()
	{
		$handler = $this->handler;

		$subject = $handler->createSubject("subject");
		$subject->setName("The new subject name");
		
		$handler->updateSubject($subject);
		
		$loadedSubject = $handler->loadSubject($subject->getId());
		$this->assertSame("The new subject name", $loadedSubject->getName());
	}

	public function testDeleteSubject()
	{
		$handler = $this->handler;

		$subject = $handler->createSubject("subject");

		$handler->deleteSubject($subject);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteThenLoadSubject()
	{
		$handler = $this->handler;

		$subject = $handler->createSubject("subject");
		$id = $subject->getId();

		$handler->deleteSubject($subject);
		$handler->loadSubject($id);
	}
}
