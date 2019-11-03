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

use Symfony\Component\OptionsResolver\OptionsResolver; 

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Agent extends AbstractAgent
{
	/**
	* Checks if it is person or organization
	* 
	* @return bool|null
	*/
	public function isPerson()
	{
		if (null !== ($agentType = $this->getAgentType()))
			return $agentType->isPersonType();
	}
	
	/**
	* @inheritdoc
	*/
    public function configureProperties(OptionsResolver $resolver)
    {
		if (null !== ($agentType = $this->getAgentType()))
			$agentType->configureProperties($resolver);
	}

	/**
	* @inheritdoc
	*/
    public function configureOptions(OptionsResolver $resolver)
    {
		if (null !== ($agentType = $this->getAgentType()))
			$agentType->configureOptions($resolver);
	}

	/**
	* @inheritdoc
	*/
    public function configureAttributes(OptionsResolver $resolver)
    {
		if (null !== ($agentType = $this->getAgentType()))
			$agentType->configureAttributes($resolver);
	}
	
}
