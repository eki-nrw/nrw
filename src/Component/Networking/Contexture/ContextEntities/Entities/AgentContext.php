<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Contexture\ContextEntities\Entities;

use Eki\NRW\Component\Networking\Contexture\ContextEntities\Context\ContextInterface;
use Eki\NRW\Component\Networking\Agent\AgentInterface;
use Eki\NRW\Mdl\Contexture\ContextEntities\Entities\Blocks;
use Eki\NRW\Mdl\Contexture\ContextEntities\Entities\EntitiesGettterInterface;

/**
* Agent Context
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class AgentContext extends Blocks
{
	public function __construct(
		AgentInterface $agent,
		ContextInterface $context, 
		EntitiesGettterInterface $getter
	)
	{
		parent::__construct($context, $getter);
		
		$this->setBoundary($agent);
	}
}
