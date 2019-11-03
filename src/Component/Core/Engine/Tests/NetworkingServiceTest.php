<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Tests;

use Eki\NRW\Component\Core\Persistence\Networking\Handler as NetworkingHandler;
use Eki\NRW\Component\Core\Engine\NetworkingService;

use Eki\NRW\Component\REA\Agent\AgentTypeBuilder;
use Eki\NRW\Component\REA\Agent\AgentBuilder;

use Eki\NRW\Component\Core\Persistence\Tests\Networking\Fixtures\Agent;
use Eki\NRW\Component\Core\Persistence\Tests\Networking\Fixtures\AgentType;

use PHPUnit\Framework\TestCase;

use stdClass;

class NetworkingServiceTest extends PrepareServiceTest
{
	public function setUp()
	{
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
		
		$this->serviceClass = NetworkingService::class;
		$this->handlerClass = NetworkingHandler::class;

		$this->extraArgs = [
			new AgentTypeBuilder('null'),
			new AgentBuilder(new AgentType)
		];
		
		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}
	
	public function testCreateAgentType()
	{
		$service = $this->service;
		
		$agentType = $service->createAgentType("default");
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	* 
	*/
	public function testCreateAgentTypeWithWrongIdentifier()
	{
		$service = $this->service;
		
		$agentType = $service->createAgentType("wrong.identifier");
	}

	public function testLoadAgentType()
	{
		$service = $this->service;
		
		$agentType = $service->createAgentType("default");
		$loadedAgentType = $service->loadAgentType($agentType->getId());
		
		$this->assertSame($loadedAgentType->getId(), $agentType->getId());
	}

	public function testUpdateAgentType()
	{
		$service = $this->service;
		
		$agentType = $service->createAgentType("default");
		$agentType->setName("The Agent Type");
		
		$service->updateAgentType($agentType);
		$loadedAgentType = $service->loadAgentType($agentType->getId());
		
		$this->assertSame("The Agent Type", $loadedAgentType->getName());
	}

	public function testDeleteAgentType()
	{
		$service = $this->service;
		
		$agentType = $service->createAgentType("default");
		
		$service->deleteAgentType($agentType);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteAgentTypeThenLoad()
	{
		$service = $this->service;
		
		$agentType = $service->createAgentType("default");
		
		$service->deleteAgentType($agentType);
		$service->loadAgentType($agentType->getId());
	}

	public function testCreateAgent()
	{
		$service = $this->service;
		
		$agentType = $service->createAgentType("default");
		$agent = $service->createAgent($agentType);
		
		$this->assertNotNull($agent);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	* 
	*/
	public function testCreateAgentWithWrongIdentifier()
	{
		$service = $this->service;
		
		$agent = $service->createAgent(new AgentType());
	}

	public function testLoadAgent()
	{
		$service = $this->service;
		
		$agentType = $service->createAgentType("default");
		$agent = $service->createAgent($agentType);
		$loadedAgent = $service->loadAgent($agent->getId());
		
		$this->assertSame($loadedAgent->getId(), $agent->getId());
	}

	public function testUpdateAgent()
	{
		$service = $this->service;
		
		$agentType = $service->createAgentType("default");
		$agent = $service->createAgent($agentType);
		$agent->setName("The Agent");
		
		$service->updateAgent($agent);
		$loadedAgent = $service->loadAgent($agent->getId());
		
		$this->assertSame("The Agent", $loadedAgent->getName());
	}

	public function testDeleteAgent()
	{
		$service = $this->service;
		
		$agentType = $service->createAgentType("default");
		$agent = $service->createAgent($agentType);
		
		$service->deleteAgent($agent);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteAgentThenLoad()
	{
		$service = $this->service;
		
		$agentType = $service->createAgentType("default");
		$agent = $service->createAgent($agentType);
		
		$service->deleteAgent($agent);
		$service->loadAgent($agent->getId());
	}
}

