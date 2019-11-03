<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Networking\Agent;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\PrepareBaseHandlerTest;
use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Agent\Handler;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Networking\Fixtures\Agent;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

class HandlerTest extends PrepareBaseHandlerTest
{
	public function setUp()
	{
		$this->handlerClass = Handler::class;
		
		$this->metadata = new Metadata(
			"agent", 
			array(
				'default' => Agent::class,
				'agent.good' => Agent::class,
				'good.best.ok' => Agent::class,
				'everything.ok' => Agent::class,
			),
			array(
				'cache_prefix' => 'agent',
				'cache_tag' => 'agent'
			)
		);

		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}

	public function testCreate()
	{
		$handler = $this->handler;
		
		$agent = $handler->create("agent.good");
		$this->assertNotNull($agent);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testCreateWrongIdentifier()
	{
		$handler = $this->handler;
		
		$agent = $handler->create("agent.wrong");
	}
	
	public function testLoad()
	{
		$handler = $this->handler;

		$agent = $handler->create("agent.good");

		$loadedAgent = $handler->load($agent->getId());
		$this->assertNotNull($loadedAgent);
		$this->assertSame($loadedAgent->getId(), $agent->getId());
	}	
	
	public function testUpdate()
	{
		$handler = $this->handler;

		$agent = $handler->create("agent.good");
		$agent->setName("The new agent name");
		
		$handler->update($agent);
		
		$loadedAgent = $handler->load($agent->getId());
		$this->assertSame("The new agent name", $loadedAgent->getName());
	}

	public function testDelete()
	{
		$handler = $this->handler;

		$agent = $handler->create("agent.good");

		$handler->delete($agent);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteThenLoad()
	{
		$handler = $this->handler;

		$agent = $handler->create("agent.good");
		$id = $agent->getId();

		$handler->delete($agent);
		$handler->load($id);
	}
}
