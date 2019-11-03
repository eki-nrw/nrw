<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Processing;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Returns persistence handler of event
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Processing\Event\Handler
	*/
	public function eventHandler();

	/**
	* Returns persistence handler of frame
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Processing\Frame\Handler
	*/
	public function frameHandler();


	/**
	* Returns persistence handler of pass
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Processing\Pass\Handler
	*/
//	public function passHandler();

	/**
	* Returns persistence handler of process
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Processing\Process\Handler
	*/
//	public function processHandler();

	/**
	* Returns persistence handler of exchange
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Processing\Exchange\Handler
	*/
//	public function exchangeHandler();

}
