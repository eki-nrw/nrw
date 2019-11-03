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

use Eki\NRW\Mdl\Working\PlanItemTypeInterface;
use Eki\NRW\Component\Processing\Frame\Exchange\HasExchangeTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeExecutePlanItem extends ExecutePlanItem implements ExchangeExecutePlanItemInterface
{
	use
		HasExchangeTrait
	;
	
	/**
	* @inheritdoc
	*/
	protected function matchPlanItemType(PlanItemTypeInterface $planItemType)
	{
		parent::matchPlanItemType($planItemType);
		
		if (!$planItemType->is('exchange'))
			throw new \InvalidArgumentException("Exchange Execute Plan Item must be exchange plan item type.");
	}
}
