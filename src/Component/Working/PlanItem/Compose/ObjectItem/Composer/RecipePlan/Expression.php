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
class Expression extends Composer
{
	/**
	* Constructor
	* 
	* @param string|object $expression
	* @param int $quantity
	* @param string $unitAlias
	* @apram array $specifications
	* 
	*/
	public function __construct($expression, $quantity, $unitAlias = '', array $specifications = [])
	{
		if (is_object($expression))
		{
			if (!method_exists($expression, "__toString"))
				throw new \InvalidArgumentException("expression has not method __toString.");
				
			$expression = new ArrayObject([$expression->__toString()]);
		}
		
		if (is_string($expression))
		{
			$expression = new ArrayObject([$expression]);
		}
		
		parent::__construct($expression, $quantity, $unitAlias, $specifications);
	}
}
