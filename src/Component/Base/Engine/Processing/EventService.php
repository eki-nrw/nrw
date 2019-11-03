<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 
 
namespace Eki\NRW\Component\Base\Engine\Processing;

use Eki\NRW\Component\Processing\Event\EventInterface;


/**
 * Event Service interface.
 */
interface EventService
{
	public function loadEvent($eventId);
	
	public function createEvent($identifier);
	
	public function deleteEvent(EventInterface $event);

	public function updateEvent(EventInterface $event);
	
	public function trigger(EventInterface $event);
}
