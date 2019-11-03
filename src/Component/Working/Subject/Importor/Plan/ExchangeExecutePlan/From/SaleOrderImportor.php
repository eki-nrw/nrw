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
use Eki\NRW\Component\Working\Events;
use Eki\NRW\Component\Working\Event\GetPlanFromRecipeEvent;

use Eki\NRW\Component\Working\Recipe\FinderInterface;
use Eki\NRW\Component\Working\Recipe\GetterInterface;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Eki\NRW\Common\Compose\ObjectItemSource\ObjectItemSource;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class SaleOrderImportor extends BaseImportor
{
	const PLAN_TYPE = 'plan.execute.exchange';

	/**
	* @var EventDispatcherInterface
	*/
	protected $eventDispatcher;

	/**
	* @var FinderInterface
	*/	
	protected $finder;
	
	/**
	* @var GetterInterface
	*/
	protected $getter;

	public function __construct(EventDispatcherInterface $eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;
	}
	
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
		return $data instanceof SaleOrderInterface;
	}
	
	/**
	* @inheritdoc
	*/
	public function import($data, &$object, array $contexts = [])
	{
		$this->_import($data, $object, $contexts);
	}

	private function _import(SaleOrderInterface $order, ExchangeExecutePlanInterface &$plan, array $contexts = [])
	{
		$recipeExchangePlan = $this->finder->find($order);
		
		// Responsibilities
		foreach($order->getParticipants() as $participant)
		{
			$responsibility = new Responsibility($participant->getRole(), $participant->getPartner()->getActor());
			$plan->setResponsibility($responsibility);
		}
		
		// Plan Items
		foreach($order->getExchangeItems() as $orderItem)
		{
			$recipePlanItem = $this->finder->find($orderItem);
			
			$planItem = $this->getter->get(
				$recipePlanItem, 
				$contexts + array(
					'saleOrderItem' => $orderItem,
				)
			);
		}
		
		//
	}
}
