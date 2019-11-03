<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\Plan\ExchangeExecutePlan\From;

use Eki\NRW\Component\Working\Subject\Importor\Plan\ExchangeExecutePlan\BaseImportor;
use Eki\NRW\Common\Compose\ObjectItem\ObjectItem;
use Eki\NRW\Common\Compose\ObjectItemSource\ObjectItemSource;
use Eki\NRW\Common\QuantityValue\QuantityValue;

use Eki\NRW\Component\Working\Plan\ExchangeRecipePlanInterface;

use Eki\NRW\Mdl\Working\Subject\DirectorInterface;
use Eki\NRW\Component\Working\Plan\ExchangeExecutePlanInterface;
use Eki\NRW\NRW\Boundary\Outgoing\Demand\CustomerOrderInterface;
use Eki\NRW\NRW\Base\Methods;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class CustomerOrderImportor extends BaseImportor
{
	const PLAN_TYPE = 'plan.execute.exchange';
	const PLAN_ITEM_TYPE = 'planitem.execute.exchange';
	
	/**
	* Checks if it can be imported
	* 
	* @param mixed $data
	* @param object $object
	* 
	* @return bool
	*/
	public function supportData($data)
	{
		return $data instanceof CustomerOrderInterface;
	}
	
	/**
	* @inheritdoc
	*/
	public function import($data, &$object, array $contexts = [])
	{
		$this->_import($data, $object, $contexts);
	}

	private function _import(CustomerOrderInterface $order, ExchangeExecutePlanInterface &$plan, array $contexts = [])
	{
		$director = $this->getDirector();
		
		if (!isset($contexts['exchange_recipe_plan']))
			throw new \RuntimeException(sprintf("Contexts don't have index '%s'", "exchange_recipe_plan"));

		if (!isset($contexts['recipe_to_execute_plan_item_converter']))
			throw new \RuntimeException(sprintf("Contexts don't have index '%s'", "recipe_to_execute_plan_item_converter"));
		
		$recipeExchangePlan = $contexts['exchange_recipe_plan'];
		if (!$recipeExchangePlan instanceof ExchangeRecipePlanInterface)
		{
			throw new \RuntimeException(sprintf("Index '%s' of contexts must be instance of ExchangeRecipePlanInterface", 'exchange_recipe_plan'));
		}
		
		$converter = $contexts['recipe_to_execute_plan_item_converter'];

		$planBuilder = $director->getBuilder(self::PLAN_TYPE, $contexts)
			->createBuilder(self::PLAN_TYPE)
		;
		$basePlanItemBuilder = $director->getBuilder(self::PLAN_ITEM_TYPE, $contexts);
		
		$planBuilder
			->setObject($plan)
			->addInfoLines('kshadhkasdsakjdkj')
			->addResponsibility(array(
				'customer' => $order->getCustomer(),
				'provider' => $order->getProvider()
			))
		;
		
		$options = [];
		foreach($order->getOrderItems() as $orderItem)
		{
			$recipePlanItem = $recipeExchangePlan->getPlanItem('deliverable');
			//$recipeObjectItem = $recipePlanItem->getObjectItem();
			
			//$resultObjectItem = $recipeTool->attain($recipeObjectItem, $orderItem, $options);
			
			//$planItem = $basePlanItemBuilder->createBuilder(self::PLAN_ITEM_TYPE)
			//	->setObjectType(self::PLAN_ITEM_TYPE)
			//	->add('object_item', null, $resultObjectItem)
			//;

			$planItem = $basePlanItemBuilder->createBuilder(self::PLAN_ITEM_TYPE)
				->setObjectType(self::PLAN_ITEM_TYPE)
				->map('object_item', null, $recipeExchangePlan->getPlanItem('deliverable')->getObjectItem())
			;
			
			$planBuilder
				->add('plan_item', self::PLAN_ITEM_TYPE, $planItem)
			;
		}

		foreach($order->getPaymentItems() as $paymentItem)
		{
			$planItem = $converter->convert(
				$recipeExchangePlan->getPlanItem('receipt'),
				null,
				array(
					'resource_type' => $paymentItem->getItem(),
					'quantity' => $paymentItem->getQuantityValue()->getQuantity(),
					'resource' => $order->getCustomer()
				)
			);
			
			$planItem = $basePlanItemBuilder->createBuilder(self::PLAN_ITEM_TYPE)
				->setObjectType(self::PLAN_ITEM_TYPE)
				->map('object_item', null, $recipeExchangePlan->getPlanItem('receipt')->getObjectItem())
			;

			$planBuilder
				->add('plan_item', self::PLAN_ITEM_TYPE, $planItem)
			;
		}
		
		$plan = $planBuilder
			->build()
		;
	}
}
