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

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class IncomingExchangeExecutePlanItem extends ExchangeExecutePlanItem
{
	/**
	* @inheritdoc
	*/
	protected function matchPlanItemType(PlanItemTypeInterface $planItemType)
	{
		parent::matchPlanItemType($planItemType);
		
		if (!$planItemType->is('incoming'))
			throw new \InvalidArgumentException("Incoming Exchange Execute Plan Item must be incoming exchange plan item type.");
	}
}
