<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\Compose\ObjectItem\Composer\RecipePlan;

use Eki\NRW\Component\Working\PlanItem\Compose\ObjectItem\Composer;
use Eki\NRW\NRW\Base\Resource\ResourceType\ResourceTypeInterface;

use ArrayObject;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ResourceTypeList extends Composer
{
	public function __construct(ArrayObject $resourceTypes, $quantity = 0, $unitAlias = '', array $specifications =[])
	{
		foreach($resourceTypes as $resourceType)
		{
			if (!$resourceType instanceof ResourceTypeInterface)
				throw new \InvalidArgumentException("One of resource type elements is not instance of ResourceTypeInterface.");
		}
		
		parent::__construct($resourceTypes, $quantity, $unitAlias, $specifications);
	}
}
