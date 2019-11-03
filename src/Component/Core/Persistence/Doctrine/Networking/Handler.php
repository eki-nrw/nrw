<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Networking;

use Eki\NRW\Component\SPBase\Persistence\Networking\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\GroupHandler;

use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Agent\Handler as AgentHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Agent\Type\Handler as AgentTypeHandler;

/**
 * Implementation of Networking Handler 
 */
class Handler extends GroupHandler implements HandlerInterface
{
	/**
	* @var AgentHandler
	*/
	protected $agentHandler;

	/**
	* @var AgentTypeHandler
	*/
	protected $agentTypeHandler;

	/**
	* @inheritdoc
	* 
	*/
	public function agentHandler()
	{
		if ($this->agentHandler === null)
		{
			$this->agentHandler = new AgentHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('agent')
			);
		}
		
		return $this->agentHandler;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function agentTypeHandler()
	{
		if ($this->agentTypeHandler === null)
		{
			$this->agentTypeHandler = new AgentTypeHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('agent_type')
			);
		}
		
		return $this->agentTypeHandler;
	}
}
