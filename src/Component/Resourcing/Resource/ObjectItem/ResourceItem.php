<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource\ObjectItem;

use Eki\NRW\Common\Compose\ObjectItem\ObjectItem;
use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;
use Eki\NRW\Component\Resourcing\Resource\HasResourceInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ResourceItem extends 
	ResourceBasedItem,
	HasResourceInterface
{
	public function __construct(ObjectItem $objectItem)
	{
		parent::__construct($objectItem, ResoureceInteface::class);
	}
	
	public function getResource()
	{
		return $this->objectItem->getItem();
	}
	
	public function setResource(ResourceInterface $resource = null)
	{
		$this->objectItem->setItem($resource);
	}
}
