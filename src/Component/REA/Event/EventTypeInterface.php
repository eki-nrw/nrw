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

use Eki\NRW\Mdl\REA\Event\EventTypeInterface as BaseEventTypeInterface;
use Eki\NRW\Common\Element\HasElementsInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface EventTypeInterface extends 
	BaseEventTypeInterface,
	HasElementsInterface
{
	/**
	* Return event type
	* 
	* @return string
	*/
	public function getEventType();
	
	/**
	* Return the name of event type
	* 
	* @return string
	*/
	public function getName();
	
	/**
	* Sets the name of event type
	* 
	* @param string $name
	* 
	* @return void
	*/
	public function setName($name);
}
