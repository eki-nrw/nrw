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
		if (isset($contexts['subjectDirector']))
			$director = $contexts['subjectDirector'];
		else
			$director = $this->getDirector();
		
		$recipeExchangePlan = $contexts['recipe_plan'];
		$workingService = $contexts['workingService'];
		$subjectTypeGetter = $contexts['subjectTypeGetter'];
		$configResolver = $contexts['configResolver'];
		
		// Responsibilities
		foreach($order->getParticipants() as $participant)
		{
			$responsibility = new Responsibility($participant->getRole(), $participant->getPartner()->getActor());
			$plan->setResponsibility($responsibility);
		}
		
		// Plan Items
		$language = new ExpressionLanguage();
		foreach($order->getExchangeItems() as $orderItem)
		{
			$recipePlanItem = $recipeExchangePlan->getPlanItem($this->getRecipePlanItemKey($orderItem));
			$planItem = $workingService->createPlanItem(
				$this->getRecipePlanItemKey($configResolver, $subjectTypeGetter($recipePlanItem)), 
				$plan
			);

			// Timing
			foreach($recipePlanItem->getTiming()->getTimes() as $timeKey => $recipeTime)
			{
				$planItemTime = $language->evaluate($recipeTime, array($timeKey => $orderItem->getTime($timeKey)));
				$planItem->setTime($timeKey, $planItemTime);
			}
			
			// Source
			$recipeSource = $recipePlanItem->getObjectItemSource();
			// From recipeSource=Type+Object+Specs+Method, create Plan
			$event = $this->eventDispatcher->dispatch(
				Events::WORKING_GET_PLAN_FROM_RECIPE, 
				new GetPlanFromRecipeEvent($workingService->loadRecipePlanByIdentifier($recipeSource->getSourceType()))
			);
			$sourcePlan = $event->getExecutePlan();
			
			$planItem->setObjectItemSource(
				(new ObjectItemSource())
					->setSourceType($subjectTypeGetter->getSubjectType($sourcePlan))
					->setSourceObject($sourcePlan)
//					->setSourceMethod()
//					->setSourceSpecifications()
			);		
			
			// Item
			$recipeItem = $recipePlanIem->getObjectItem();
			$obj = $language->evaluate($recipeItem->getItem(), array('item' => $orderItem->getItem()));
			
			$workignService->updateSubject($planItem);
		}

		$workingService->updateSubject($plan);
	}
	
	private function getRecipePlanItemKey(ExchangeItemInterface $orderItem)
	{
		if ($orderItem->getExchangeItemType()->is("deliverable"))
			return "deliverable";
			
		if ($orderItem->getExchangeItemType()->is("payment"))
			return "payment";
	}
	
	private function getExecutePlanItemType(ConfigResolverInterface $configResolver, $recipePlanItemType)
	{
		$list = $configResolver->getParameter('eki_nrw.working.recipe_to_execute_plan.execute_plan_item.type.list');
		return $list[$recipePlanItemType];
	}
}
