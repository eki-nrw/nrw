<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\BaseGroupHandlerTest;

use Eki\NRW\Component\Core\Persistence\Doctrine\Handler;

use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Handler as NetworkingHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Handler as ResourcingHandler;

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
	
	public function getSettings()
	{
		return [
			[],
		];
	}
	
	public function testNetworkingHandler()
	{
		$handler = $this->handler;
		
		$networkingHandler = $handler->networkingHandler();
		$this->assertNotNull($networkingHandler);
		$this->assertInstanceOf(NetworkingHandler::class, $networkingHandler);
	}
}
