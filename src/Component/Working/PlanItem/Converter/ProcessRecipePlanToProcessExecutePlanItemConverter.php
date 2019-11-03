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

use Eki\NRW\Component\Working\PlanItem\ProcessRecipePlanItemInterface;
use Eki\NRW\Component\Working\PlanItem\ProcessExecutePlanItemInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessRecipeToProcessExecutePlanItemConverter extends ExecuteRecipeToExecutePlanItemConverter
{
	public function __construct(
		$executePlanItemType = 'plan_item.execute.process',
		$recipePlanClass = '\Eki\NRW\Component\Working\PlanItem\ProcessRecipePlanItemInterface',
		$planClass = 'Eki\NRW\Component\Working\PlanItem\ProcessExecutePlanItemInterface'
	)
	{
		parent::__construct($executePlanItemType, $recipePlanClass, $planClass);
	}
}
