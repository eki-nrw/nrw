<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Networking;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\PrepareGroupHandlerTest;

use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Handler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Agent\Handler as AgentHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Agent\Type\Handler as AgentTypeHandler;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Networking\Fixtures\Agent;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Networking\Fixtures\AgentType;

class HandlerTest extends PrepareGroupHandlerTest
{
	public function setUp()
	{
		$this->handlerClass = Handler::class; 
		
		$this->addToRegistry("agent",
			array(
				'classes' => array(
					'default' => Agent::class,
				),
				'cache_prefix' => 'agent',
				'cache_tag' => 'agent'
			)
		);
		$this->addToRegistry("agent_type",
			array(
				'classes' => array(
					'default' => AgentType::class,
				),
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
	
	public function testAgentHandler()
	{
		$handler = $this->handler;
		
		$agentHandler = $handler->agentHandler();
		$this->assertNotNull($agentHandler);
		$this->assertInstanceOf(AgentHandler::class, $agentHandler);
	}

	public function testAgentHandlerOperations()
	{
		$handler = $this->handler;
		
		$agentHandler = $handler->agentHandler();
		$agent = $agentHandler->create("default");
		
		$this->assertNotNull($agent);
		
		$id = $agent->getId();
		$loadedAgent = $agentHandler->load($id);
		
		$this->assertNotNull($loadedAgent);
		$this->assertSame($agent->getId(), $loadedAgent->getId());
		
		$randomName = str_shuffle("abcdefghijk 621668966296329");
		$agent->setName($randomName);
		$agentHandler->update($agent);
		$loadedAgent = $agentHandler->load($id);
		
		$this->assertSame($randomName, $loadedAgent->getName());
		
		$agentHandler->delete($loadedAgent);
	}

	public function testAgentTypeHandler()
	{
		$handler = $this->handler;

		$agentTypeHandler = $handler->agentTypeHandler();
		$this->assertNotNull($agentTypeHandler);
		$this->assertInstanceOf(AgentTypeHandler::class, $agentTypeHandler);
	}

	public function testAgentTypeHandlerOperations()
	{
		$handler = $this->handler;

		$agentTypeHandler = $handler->agentTypeHandler();
		$agentType = $agentTypeHandler->create("default");
		
		$this->assertNotNull($agentType);
		
		$id = $agentType->getId();
		$loadedAgentType = $agentTypeHandler->load($id);
		
		$this->assertNotNull($loadedAgentType);
		$this->assertSame($agentType->getId(), $loadedAgentType->getId());
		
		$randomName = str_shuffle("abcdefghijk 621668966296329");
		$agentType->setName($randomName);
		$agentTypeHandler->update($agentType);
		$loadedAgentType = $agentTypeHandler->load($id);
		
		$this->assertSame($randomName, $loadedAgentType->getName());
		
		$agentTypeHandler->delete($loadedAgentType);
	}
}
