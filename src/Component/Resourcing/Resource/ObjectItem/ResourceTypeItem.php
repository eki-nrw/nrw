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
use Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface;
use Eki\NRW\Component\Resourcing\Resource\Type\HasResourceTypeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ResourceTypeItem extends 
	ResourceBasedItem,
	HasResourceTypeInterface
{
	public function __construct(ObjectItem $objectItem)
	{
		parent::__construct($objectItem, ResoureceTypeInteface::class);
	}

	public function getResourceType()
	{
		return $this->objectItem->getItem();
	}
	
	public function setResourceType(ResourceTypeInterface $resourceType = null)
	{
		$this->objectItem->setItem($resourceType);
	}
}
