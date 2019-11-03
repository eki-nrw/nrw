<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem;

use Eki\NRW\Component\Working\PlanItem\ExchangeRecipePlanItemInterface;
use Eki\NRW\Component\Working\PlanItem\ExchangePlanItemInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeRecipeToExchangeExecutePlanItemConverter extends ExecuteRecipeToExecutePlanItemConverter
{
	public function __construct(
		$executePlanItemType = 'plan_item.execute.exchange',
		$recipePlanClass = '\Eki\NRW\Component\Working\PlanItem\ExchangeRecipePlanItemInterface',
		$planClass = 'Eki\NRW\Component\Working\PlanItem\ExchangeExecutePlanItemInterface'
	)
	{
		parent::__construct($executePlanItemType, $recipePlanClass, $planClass);
	}
}
