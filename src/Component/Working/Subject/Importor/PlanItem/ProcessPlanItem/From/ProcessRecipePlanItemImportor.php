<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\PlanItem\ProcessPlanItem\From;

use Eki\NRW\Component\Working\PlanItem\ProcessPlanItemInterface;
use Eki\NRW\Component\Working\PlanItem\ProcessRecipePlanItemInterface;
use Eki\NRW\Common\Compose\ObjectItem\ObjectItem;
use Eki\NRW\Common\Compose\ObjectItemSource\ObjectItemSource;
use Eki\NRW\Common\QuantityValue\QuantityValue;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessRecipePlanItemImportor extends BaseImportor
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
			
		if ($data instanceof ProcessRecipePlanItemInterface)
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
		RecipePlanItemInterface $recipePlanItem, 
		ProcessPlanItemInterface &$processPlanItem, 
		array $contexts 
	)
	{
		$quantityCoef = isset($contexts['quantity']) ? $contexts['quantity'] : 1; 
		$baseObjectItem = $recipePlanItem->getObjectItem();
		$baseObjectItemSource = $recipePlanItem->getObjectItemSource();
		$baseObjectItemSource = null !== $baseObjectItemSource ? $baseObjectItemSource : new ObjectItemSource();
		
		$processPlanItem
			->setObjectItem(
				(new ObjectItem())
					->setItem($baseObjectItem->getItem())
					->setQuantityValue(new QuantityValue(
						$quantityCoef * $baseObjectItem->getQuantityValue()->getQuantity(),
						$baseObjectItem->getQuantityValue()->getUnitAlias()
					))
					->setSpecifications($recipePlanItem->getObjectItem()->getSpecifications())
			)
			->setObjectItemSource(
				(new ObjectItemSource)
					->setSourceType($baseObjectItemSource->getSourceType())
					->setSourceObject($baseObjectItemSource->getSourceObject())
					->setSourceMethod($baseObjectItemSource->getSourceMethod())
					->setSourceSpecifications($baseObjectItemSource->getSourceSpecifications())   // dirty
			)
		;
	}
}
