<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Networking;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Returns persistence handler of agent
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Networking\Agent\Handler
	*/
	public function agentHandler();

	/**
	* Returns persistence handler of agent type
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Networking\Agent\Type\Handler
	*/
	public function agentTypeHandler();
}
