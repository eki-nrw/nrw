<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Networking\Agent\Type;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\PrepareBaseHandlerTest;
use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Agent\Type\Handler;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Networking\Fixtures\AgentType;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

class HandlerTest extends PrepareBaseHandlerTest
{
	public function setUp()
	{
		$this->handlerClass = Handler::class; 
		
		$this->metadata = new Metadata(
			"agent_type", 
			array(
				'default' => AgentType::class,
				'agent_type.good' => AgentType::class,
				'good_type.best.ok' => AgentType::class,
				'everything.ok' => AgentType::class,
			),
			array(
				'cache_prefix' => 'agent_type',
				'cache_tag' => 'agent_type'
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
		
		$agentType = $handler->create("agent_type.good");
		$this->assertNotNull($agentType);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testCreateWrongIdentifier()
	{
		$handler = $this->handler;
		
		$agent = $handler->create("agent_type.wrong");
	}
	
	public function testLoad()
	{
		$handler = $this->handler;

		$agentType = $handler->create("agent_type.good");

		$loadedAgentType = $handler->load($agentType->getId());
		$this->assertNotNull($loadedAgentType);
		$this->assertSame($loadedAgentType->getId(), $agentType->getId());
	}	
	
	public function testUpdate()
	{
		$handler = $this->handler;

		$agentType = $handler->create("agent_type.good");
		$agentType->setName("The new agent type name");
		
		$handler->update($agentType);
		
		$loadedAgentType = $handler->load($agentType->getId());
		$this->assertSame("The new agent type name", $loadedAgentType->getName());
	}

	public function testDelete()
	{
		$handler = $this->handler;

		$agentType = $handler->create("agent_type.good");

		$handler->delete($agentType);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteThenLoad()
	{
		$handler = $this->handler;

		$agentType = $handler->create("agent_type.good");
		$id = $agentType->getId();

		$handler->delete($agentType);
		$handler->load($id);
	}
}
