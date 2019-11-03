<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Agent\Provider;

use Eki\NRW\Component\Networking\Agent\AgentInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ProviderInterface
{
	/**
	* Checks if the provider supports $argument
	* 
	* @param mixed $arguments
	* 
	* @return bool
	*/
	public function support($arguments);
	
	/**
	* Get agent matched condition $arguments
	* 
	* @param mixed $arguments
	* @param array $contexts
	* 
	* @return AgentInterface
	*/
	public function get($arguments, array $contexts = []);

	/**
	* Get all agents matched condition $arguments
	* 
	* @param mixed $arguments
	* @param array $contexts
	* 
	* @return AgentInterface[]
	*/	
	public function getAll($arguments, array $contexts = []);
}
