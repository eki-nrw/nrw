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
use Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ResourceBasedItem extends ObjectItem
{
	/**
	* @var ObjectItem
	*/
	protected $objectItem;
	
	public function __construct(ObjectItem $objectItem, $resourceBasedClass)
	{
		if ($resourceBasedClass !== ResourceInterface::class and $resourceBasedClass !== ResourceTypeInterface::class
			or !class_exists($resourceBasedClass)
		)
			throw new \InvalidArgumentException(sprintf(
				"'resourceBasedClass' parameters must be one of [%s, %s]. Given %s.",
				ResourceInterface::class,
				ResourceTypeInterface::class,
				get_class($resourceBasedClass)
			));
		
		if (null === ($item = $objectItem->getItem()))
			throw new \InvalidArgumentException("Object Item has no item.");
			
		if (!$item instanceof $resourceBasedClass)
			throw new \InvalidArgumentException(sprintf(
				"Object Item must have item that is instance of %s. Given %s.",
				$resourceBasedClass, get_class($item)
			));
			
		$this->objectItem = $objectItem;
	}
	
	public function getMethod()
	{
		$specs = $this->objectItem->getSpecifications();
		return $specs['rea_method'];
	}
	
	public function setMethod($method)
	{
		$specs = $this->objectItem->getSpecifications();
		$specs['rea_method'] = $method;
		$this->objectItem->setSpecifications($specs);
	}
}
