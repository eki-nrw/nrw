<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Networking\Agent;

use Eki\NRW\Component\Networking\Agent\AgentInterface;

use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException;


/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create new agent object
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Networking\Agent\AgentInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	*/
	public function create($identifier);
	
	/**
	* Load agent object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Networking\Agent\AgentInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	* @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException
	* 
	*/
	public function load($id);
	
	/**
	* Delete given agent
	* 
	* @param \Eki\NRW\Component\Networking\Agent\AgentInterface $agent
	* 
	* @return void
	*/	
	public function delete(AgentInterface $agent);
	
	/**
	* Update a agent identified by $id
	* 
	* @param \Eki\NRW\Component\Networking\Agent\AgenteInterface $agent
	* 
	* @return void
	*/
	public function update(AgentInterface $agent);
}
