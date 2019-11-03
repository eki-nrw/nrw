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
use Eki\NRW\Component\Processing\Frame\Exchange\ExchangeTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeExecutePlan extends ExecutePlan implements ExchangeExecutePlanInterface
{
	use
		HasExchangeTrait
	;
	
	/**
	* @inheritdoc
	*/
	protected function matchPlanType(PlanTypeInterface $planType)
	{
		parent::matchPlanType($planType);
		
		if (!$planType->is('exchange'))
			throw new \InvalidArgumentException("Exchange Execute Plan must be exchange execute plan type.");
	}
}
