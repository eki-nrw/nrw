<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Event;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface EventAwareInterface
{
	/**
	* Set event
	* 
	* @param EventInterface $event
	* 
	* @return void
	*/
	public function setEvent(EventInterface $event = null);
}
