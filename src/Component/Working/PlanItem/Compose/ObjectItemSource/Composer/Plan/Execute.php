<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\Compose\ObjectItemSource\Composer\Plan;

use Eki\NRW\Component\Working\PlanItem\Compose\ObjectItemSource\Composer\Plan;

use Eki\NRW\Component\Working\Plan\PlanExecuteInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class Execute extends Plan
{
	public function __construct(PlanExecuteInterface $plan)
	{
		parent::__construct($plan);
	}
}
