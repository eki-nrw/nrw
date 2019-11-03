<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Networking\Fixtures;

use Eki\NRW\Component\Networking\Agent\Type\AgentType as BaseAgentType;

class AgentType extends BaseAgentType
{
	public function getAgentType()
	{
		return "default";
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
}
