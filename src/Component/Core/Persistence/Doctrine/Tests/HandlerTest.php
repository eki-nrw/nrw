<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests;

use Eki\NRW\Component\Core\Persistence\Doctrine\Handler;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\CommonHelper;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Networking\Fixtures\Agent;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Networking\Fixtures\AgentType;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Resourcing\Fixtures\Resource;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Resourcing\Fixtures\ResourceType;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Relating\Fixtures\Relation;

use Eki\NRW\Common\Res\Factory\FactoryInterface;

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

		$this->addToRegistry("resource",
			array(
				'classes' => array(
					'default' => Resource::class,
				),
				'cache_prefix' => 'resource',
				'cache_tag' => 'resource'
			)
		);
		$this->addToRegistry("resource_type",
			array(
				'classes' => array(
					'default' => ResourceType::class,
				),
				'cache_prefix' => 'resource_type',
				'cache_tag' => 'resource_type'
			)
		);

		$this->addToRegistry("relating",
			array(
				'classes' => array(
					'default' => Relation::class,
					'relation' => Relation::class,
					'relationship' => Relation::class,
					'group' => Relation::class,
				),
				'cache_prefix' => 'relating',
				'cache_tag' => 'relating'
			)
		);

		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}

	public function testNetworkingHandler()
	{
		// Persistence Handler
		$handler = $this->handler;

		// Group Handler and Base Handlers		
		$networkingHandler = $handler->networkingHandler();
		$agentTypeHandler = $networkingHandler->agentTypeHandler();
		$agentHandler = $networkingHandler->agentHandler();
		
		// Agent Type
		$agentType = $agentTypeHandler->create("default");
		$this->assertNotNull($agentType);
		$this->assertSame("default", $agentType->getAgentType());
		
		$agentType->setName("Default Agent Type");
		$agentTypeHandler->update($agentType);
		
		$loadedAgentType = $agentTypeHandler->load($agentType->getId());
		$this->assertSame("Default Agent Type", $loadedAgentType->getName());
		
		$agentTypeHandler->delete($loadedAgentType);
		
		// Agent
		$agent = $agentHandler->create("default");
		$this->assertNotNull($agent);

		$agent->setName("Default Agent");
		$agentHandler->update($agent);
		
		$loadedAgent = $agentHandler->load($agent->getId());
		$this->assertSame("Default Agent", $loadedAgent->getName());
		
		$agentHandler->delete($loadedAgent);
	}

	public function testResourcingHandler()
	{
		// Persistence Handler
		$handler = $this->handler;

		// Group Handler and Base Handlers		
		$resourcingHandler = $handler->resourcingHandler();
		$resourceTypeHandler = $resourcingHandler->resourceTypeHandler();
		$resourceHandler = $resourcingHandler->resourceHandler();
		
		// Resource Type
		$resourceType = $resourceTypeHandler->create("default");
		$this->assertNotNull($resourceType);
		$this->assertSame("default", $resourceType->getResourceType());
		
		$resourceType->setName("Default Resource Type");
		$resourceTypeHandler->update($resourceType);
		
		$loadedResourceType = $resourceTypeHandler->load($resourceType->getId());
		$this->assertSame("Default Resource Type", $loadedResourceType->getName());
		
		$resourceTypeHandler->delete($loadedResourceType);
		
		// Resource
		$resource = $resourceHandler->create("default");
		$this->assertNotNull($resource);

		$resource->setName("Default Resource");
		$resourceHandler->update($resource);
		
		$loadedResource = $resourceHandler->load($resource->getId());
		$this->assertSame("Default Resource", $loadedResource->getName());
		
		$resourceHandler->delete($loadedResource);
	}


	public function testRelatingHandler()
	{
		// Persistence Handler
		$handler = $this->handler;

		// Base Handler
		$relatingHandler = $handler->relatingHandler();

		// Creation
		$relation = $relatingHandler->createRelation("relation", null);
		$this->assertNotNull($relation);
		
		// Updating
		$relation->setParameter("the_param_name", "the given parameter value");
		$relatingHandler->updateRelation($relation);
		
		// Loading
		$loadedRelation = $relatingHandler->loadRelation($relation->getId());
		$this->assertNotNull($loadedRelation);
		$this->assertSame("the given parameter value", $loadedRelation->getParameter("the_param_name"));
		
		// Deletion
		$relatingHandler->deleteRelation($loadedRelation);
	}

	public function testTransactionHandler()
	{
		$handler = $this->handler;

		$transactionHandler = $handler->transactionHandler();
	}
}

