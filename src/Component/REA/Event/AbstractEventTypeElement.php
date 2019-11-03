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

use Eki\NRW\Common\Element\AbstractElement;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractEventTypeElement extends AbstractElement
{
	protected $eventType;
	
	/**
	* @inheritdoc
	*/
	public function getElementType()
	{
		return 'event_type_element';
	}
	
	public function setEventType(EventTypeInterface $eventType)
	{
		$this->eventType = $eventType;	
	}
	
	public function getContainer()
	{
		return $this->eventType;
	}
}
