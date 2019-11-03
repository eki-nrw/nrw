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

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ResourceType extends Composer
{
	public function __construct(
		ResourceTypeInterface $resourceType, 
		$quantity, 
		$unitAlias = '', 
		array $specifications = []
	)
	{
		$unitAlias = empty($unitAlias) ? $resourceType->getDefaultUnitAlias() : $unitAlias;
		
		parent::__construct($resourceType, $quantity, $unitAlias, $specifications);
	}
}
