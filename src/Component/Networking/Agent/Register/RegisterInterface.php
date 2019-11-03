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
interface RegisterInterface
{
	/**
	* Checks if the register supports $argument
	* 
	* @param mixed $arguments
	* 
	* @return bool
	*/
	public function support($arguments);
	
	/**
	* Create new agent matched condition $arguments
	* 
	* @param mixed $arguments
	* 
	* @return AgentInterface
	*/
	public function register($arguments);
}
