<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Agent\Type;

use Eki\NRW\Component\Networking\Agent\AgentInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class UserAgentType extends IndividualAgentType
{
	/**
	* @inheritdoc
	*/
	public function getAgentType()
	{
		return 'individual.user';		
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function is($thing)
	{
		if ($thing === 'user')
			return true;
		
		return parent::is($thing);
	}
	
	public function getUserId(AgentInterface $agent)
	{
		if ($agent->getAgentType()->getAgentType() !== $this->getAgentType())
			throw new \InvalidArgumentException(sprintf("Agent don't belong to this type '%s'.", $this->getAgentType()));
			
		return $agent->getProperty('userId');
	}

	public function setUserId(AgentInterface $agent, $userId)
	{
		if ($agent->getAgentType()->getAgentType() !== $this->getAgentType())
			throw new \InvalidArgumentException(sprintf("Agent don't belong to this type '%s'.", $this->getAgentType()));
			
		return $agent->setProperty('userId', $userId);
	}
}
