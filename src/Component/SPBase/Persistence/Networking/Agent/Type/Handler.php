<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Networking\Agent\Type;

use Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create new agent type object
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface
	*/
	public function create($identifier);

	/**
	* Load agent type objectby given id
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface
	*/
	public function load($id);

	/**
	* Load agent type object by identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface
	*/
	public function loadByIdentifier($identifier);
	
	/**
	* Delete given agent type
	* 
	* @param \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface $agentType
	* 
	* @return void
	*/	
	public function delete(AgentTypeInterface $agentType);
	
	/**
	* Update a agent type identified
	* 
	* @param \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface $agentType
	* 
	* @return void
	*/
	public function update(AgentTypeInterface $agentType);
}
