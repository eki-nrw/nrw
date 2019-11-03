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

use Eki\NRW\Component\Working\Plan\ProcessExecutePlanInterface;
use Eki\NRW\Component\Working\PlanItem\Compose\OnjectItemSource\Composer\Method\Produce;
use Eki\NRW\Component\Working\PlanItem\Compose\OnjectItemSource\Composer\Method\Inventory;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Process extends Execute
{
	public function __construct(ProcessExecutePlanInterface $plan)
	{
		parent::__construct($plan);
	}
	
	/**
	* @inheritdoc
	*/
	public function getAvailableMethods()
	{
		return array(
			Inventory::getMethodName(),
			Produce::getMethodName(),
		);
	}
}
