<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Processing\Event;

use Eki\NRW\Component\Processing\Event\EventInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create a new event of type $identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Processing\Event\EventInterface
	* @throws
	*/
	public function create($identifier);
	
	/**
	* Load event object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Processing\Event\EventInterface
	*/
	public function load($id);
	
	/**
	* Delete given event
	* 
	* @param \Eki\NRW\Component\Processing\Event\EventInterface $event
	* 
	* @return void
	*/	
	public function delete(EventInterface $event);
	
	/**
	* Update a event identified by $id
	* 
	* @param \Eki\NRW\Component\Processing\Event\EventeInterface $event
	* 
	* @return void
	*/
	public function update(EventInterface $event);
}
