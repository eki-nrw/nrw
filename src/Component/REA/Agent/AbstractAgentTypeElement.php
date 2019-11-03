<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Agent;

use Eki\NRW\Common\Element\AbstractElement;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractAgentTypeElement extends AbstractElement
{
	protected $agentType;
	
	/**
	* @inheritdoc
	*/
	public function getElementType()
	{
		return 'agent_type_element';
	}
	
	public function setAgentType(AgentTypeInterface $agentType)
	{
		$this->agentType = $agentType;	
	}
	
	public function getContainer()
	{
		return $this->agentType;
	}
}
