<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Agent;

use Eki\NRW\Component\REA\Agent\AbstractAgent as BaseAbstractAgent;
use Eki\NRW\Component\Networking\Agent\Relationship\AssocationInterface;
use Eki\NRW\Common\Res\Model\ResTrait;
use Eki\NRW\Common\Res\Model\TimestampableTrait;

use Symfony\Component\OptionsResolver\OptionsResolver; 

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractAgent extends BaseAbstractAgent implements AgentInterface
{
	use 
		ResTrait,
		TimestampableTrait		
	;
	
	/**
	* @inheritdoc
	*/
	public function supportAssociation(AssocationInterface $association)
	{
		if ($this !== $association->getAgent())
			return false;

		if (null !== ($agentType = $this->getAgentType()))
		{
			$resolver = new OptionResolver();
			$agentType->configureSupportedAssociations($resolver);
			
			$resolvedAssocations = $resolver->resolve([]);
			if (!in_array($association->getType(), $resolvedAssocations['supported_associations']))
				return false;
				
			if (null !== ($otherAgent = $association->getOtherAgent()))
			{
				return $this->acceptAssociateWith($otherAgent);
			}
		}
		
		return false;
	}	
	
	/**
	* Determines to accept an association with agent $otherAgent
	* 
	* @param AgentInterface $otherAgent
	* 
	* @return bool
	*/
	protected function acceptAssociateWith(AgentInterface $otherAgent)
	{
		return false;
	}
}
