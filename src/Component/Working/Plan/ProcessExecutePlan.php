<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Plan;

use Eki\NRW\Mdl\Working\PlanTypeInterface;
use Eki\NRW\Component\Processing\Frame\Process\HasProcessTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessExecutePlan extends ExecutePlan implements ProcessExecutePlanInterface
{
	use
		HasProcessTrait
	;
	
	/**
	* @inheritdoc
	*/
	protected function matchPlanType(PlanTypeInterface $planType)
	{
		parent::matchPlanType($planType);
		
		if (!$planType->is('process'))
			throw new \InvalidArgumentException("Process Execute Plan must be process execute plan type.");
	}
}
