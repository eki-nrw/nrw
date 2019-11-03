<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Agent\Creator;

use Eki\NRW\Component\Networking\Agent\AgentInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface CreatorInterface
{
	/**
	* Checks if the creator supports $argument
	* 
	* @param mixed $argument
	* 
	* @return bool
	*/
	public function support($argument);
	
	/**
	* Create new agent matched condition $argument
	* 
	* @param mixed $argument
	* @param array $context
	* 
	* @return \Eki\NRW\Component\Networking\Agent\AgentInterface
	*/
	public function create($argument, array $contexts = []);
}
