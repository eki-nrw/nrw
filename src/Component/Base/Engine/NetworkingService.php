<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine;

use Eki\NRW\Component\Networking\Agent\AgentInterface;
use Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface;

/**
 * Networking Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface NetworkingService
{
	/**
	* Load agent type by id
	* 
	* @param mixed $agentTypeId
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException
	*/
	public function loadAgentType($agentTypeId);
	
	/**
	* Load a reousrce type by identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException
	*/
	public function loadAgentTypeByIdentifier($identifier);

	/**
	* Create a new agent type by agent type identifier
	* 
	* @param string $identifier 
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	*/
	public function createAgentType($identifier);

	/**
	* Delete an agent type
	* 
	* @param \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface $agentType
	* 
	* @return void
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	*/	
	public function deleteAgentType(AgentTypeInterface $agentType);
	
	/**
	* Update an agent type
	* 
	* @param \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface $agentType
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* 
	* @return void
	*/
	public function updateAgentType(AgentTypeInterface $agentType);

	/**
	* Create new agent of an agent type $agentTypeId
	* 
	* @param \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface $agentType
	* 
	* @return AgentInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	*/
	public function createAgent(AgentTypeInterface $agentType);
	
	/**
	* Load an agent by id
	* 
	* @param mixed $agentId
	* 
	* @return \Eki\NRW\Component\Networking\Agent\AgentInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException
	*/
	public function loadAgent($agentId);
	
	/**
	* Delete an agent
	* 
	* @param \Eki\NRW\Component\Networking\Agent\AgentInterface $agent
	* 
	* @return void
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	* @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException
	*/
	public function deleteAgent(AgentInterface $agent);
	
	/**
	* Update a resouece entity to persistence storage 
	* 
	* @param \Eki\NRW\Component\Networking\Agent\AgentInterface $agent
	* 
	* @return void
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	*/
	public function updateAgent(AgentInterface $agent);
	
	/**
	* Make an association between two agents
	* 
	* @param AgentInterface $agent
	* @param AgentInterface $otherAgent
	* @param string $associationType
	* 
	* Ex.: $associationType:
	* + 'member'
	* + 'child'
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Relationship\AssociationInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	*/
	public function associateAgents(AgentInterface $agent, AgentInterface $otherAgent, $associationType);
	
	/**
	* Gets one association of the given association type between two agents
	* 
	* @param AgentInterface $agent
	* @param AgentInterface $otherAgent
	* @param string $associationType
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Relationship\AssociationInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	*/
	public function getAssociation(AgentInterface $agent, AgentInterface $otherAgent, $associationType);

	/**
	* Gets all associations between two agents
	* 
	* @param AgentInterface $agent
	* @param string|null $associationType Null if any association type
	* @param AgentInterface|null $otherAgent
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Relationship\AssociationInterface[]
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	*/
	public function getAssociations(AgentInterface $agent, $associationType = null, AgentInterface $otherAgent = null);
}
