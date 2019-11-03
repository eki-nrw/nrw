<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\Event;

use Eki\NRW\Common\Compose\ObjectItemSource\ObjectItemSourceInterface;
use Eki\NRW\Common\Compose\ObjectItem\ObjectItemInterface;

use Symfony\Component\EventDispatcher\GenericEvent;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class QueryObjectItemSourceEvent extends GenericEvent
{
	public function __construct(ObjectItemInterface $objectItem)
	{
		parent::__construct($objectItem);
	}
	
	public function getObjectItem()
	{
		return $this->getSubject();
	}
	
	public function setObjectItemSource(ObjectitemSourceInterface $objectItemSource)
	{
		$this->setArgument('object_item_source', $objectItemSource);
	}
	
	public function getObjectItemSource()
	{
		return $this->getArgument('object_item_source');
	}
}
