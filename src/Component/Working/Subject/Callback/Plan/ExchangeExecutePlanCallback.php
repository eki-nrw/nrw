<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Callback\Plan;

use Eki\NRW\Mdl\Working\Subject\Callback\Plan\ExecutePlanCallback;
use Eki\NRW\Common\REA\Processing\Exchange\ExchangeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeExecutePlanCallback extends ExecutePlanCallback
{
	/**
	* @inheritdoc
	*/
	public function getCallbackType()
	{
		return 'plan.execute.exchange';
	}

	protected function addPlanItemSupport($type, $data)
	{
		if (false === parent::addPlanItemSupport($type, $data))
			return false;

		$planItem = $data;
		if (null === ($planItemType = $planItem->getPlanItemType()))
			return false;
		if ($planItemType->is('exchange') === false)
			return false;
			
		return true;
	}

	protected function addExchange($type, $data)
	{
		$this->getPlan()->setSolution($data);
	}
	
	protected function addExchangeSupport($type, $data)
	{
		return $data instanceof ExchangeInterface;
	}
}
