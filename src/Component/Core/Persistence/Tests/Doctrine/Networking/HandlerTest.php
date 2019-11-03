<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Networking;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\BaseGroupHandlerTest;

use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Handler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Agent\Handler as AgentHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Agent\Type\Handler as AgentTypeHandler;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Networking\Fixtures\Agent;
use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Networking\Fixtures\AgentType;

class HandlerTest extends BaseGroupHandlerTest
{
	private $handler;
	
	public function setUp()
	{
		$this->addToRegistry(
			"agent",
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
	
	public function testAgentHandler()
	{
		$handler = $this->handler;
		
		$agentHandler = $handler->agentHandler();
		$this->assertNotNull($agentHandler);
		$this->assertInstanceOf(AgentHandler::class, $agentHandler);
	}

	public function testAgentTypeHandler()
	{
		$handler = $this->handler;
		
		$agentTypeHandler = $handler->agentTypeHandler();
		$this->assertNotNull($agentTypeHandler);
		$this->assertInstanceOf(AgentTypeHandler::class, $agentTypeHandler);
	}
}
