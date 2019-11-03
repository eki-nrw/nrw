<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\PlanItem\ExchangePlanItem\From;

use Eki\NRW\NRW\Order\OrderItemInterface;
use Eki\NRW\Component\Working\PlanItem\ExchangePlanItemInterface;

use Eki\NRW\Common\Compose\ObjectItem\ObjectItem;
use Eki\NRW\Common\Compose\ObjectItemSource\ObjectItemSource;
use Eki\NRW\Common\QuantityValue\QuantityValue;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class OrderItemImportor extends BaseImportor
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
			
		if ($data instanceof OrderItemInterface)
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
		OrderItemInterface $orderItem, 
		ExchangePlanItemInterface &$exchangePlanItem, 
		array $contexts 
	)
	{
		$exchangePlanItem
			->setObjectItem(
				(new ObjectItem())
					->setItem($orderItem->getObjectItem()->getItem())
					->setQuantityValue(
						new QuantityValue(
							$orderItem->getObjectItem()->getQuantityValue()->getQuantity(),
							$orderItem->getObjectItem()->getQuantityValue()->getUnitAlias()
						))
					->setSpecifications([])
			)
			->setObjectItemSource($this->queryObjectItemSource())
			->setTimes(array(
				'start_date' => $orderItem->getOrder()->getTime('start_date')
				'due_date' => $orderItem->getOrder()->getTime('due_date')
			))
		;
	}
	
	/**
	* Query object item source
	* 
	* @return ObjectItemSourceInterface
	*/
	abstract protected function queryObjectItemSource();
}
