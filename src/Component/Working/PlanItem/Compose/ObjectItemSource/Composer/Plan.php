<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\Compose\ObjectItemSource\Composer;

use Eki\NRW\Component\Working\Plan\PlanInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class Plan extends Composer
{
	public function __construct(PlanInterface $plan)
	{
		$type = $plan->getPlanType()->getPlanType();
		$obj = $plan;
		
		parent::__construct($type, $obj);
	}
}
