<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\Activity\ExchangeActivity\From;

use Eki\NRW\Component\Working\Subject\Importor\Activity\ExchangeActivity\BaseImportor;
use Eki\NRW\Common\Compose\ObjectItem\ObjectItem;
use Eki\NRW\Common\QuantityValue\QuantityValue;

use Eki\NRW\Component\Working\Activity\ExchangeActivityInterface;
use Eki\NRW\Component\Working\PlanItem\ExchangeExecutePlanItemInterface;

use Eki\NRW\NRW\Base\Methods;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeExecutePlanItemImportor extends BaseImportor
{
	/**
	* Checks if it can be imported
	* 
	* @param mixed $data
	* @param object $object
	* 
	* @return bool
	*/
	public function support($data, $object)
	{
		if (!parent::support($data, $object))
			return false;
			
		if ($data instanceof ExchangeExecutePlanItemInterface)
			return true;
	}
	
	/**
	* @inheritdoc
	*/
	public function import($data, &$object, array $contexts = [])
	{
		$this->_import($data, $object, $contexts);
	}

	private function _import(
		ExchangeExecutePlanItemInterface $planItem, 
		ExchangeActivityInterface &$activity, 
		array $contexts
	)
	{
		$activity
			->setName($planItem->getName())
			->setObjectItem(
				(new ObjectItem())
					->setItem($planItem->getObjectItem()->getItem())  // ???? Resource or Resource Type
					->setQuantityValue(clone $planItem->getObjectItem()->getQuantityValue())
					->setSpecifications($planItem->getObjectItem()->getSpecifications())
			)
			->setTimes($planItem->getTimes())
			->setSpecifications($planItem->getSpecifications())
			->setResponsibility($contexts['activity_taker'])						// dirty
			->setExchange($planItem->getPlan()->getExchange())
		;
		
		return $activity;
	}
}
