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

use Eki\NRW\Component\Working\PlanItem\ExchangePlanItemInterface;
use Eki\NRW\Mdl\Working\BuildingSubjectInterface;
use Eki\NRW\Common\Compose\ObjectItem\ObjectItem;
use Eki\NRW\Common\Compose\ObjectItemSource\ObjectItemSource;
use Eki\NRW\Common\QuantityValue\QuantityValue;

use Eki\NRW\Component\Working\ConverterInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeRecipePlanItemImportor extends BaseImportor
{
	/**
	* @var ConverterInterface
	*/
	private $converter;
	
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
			
		if ($data instanceof ExchangeRecipePlanItemInterface)
			return true;
	}
	
	/**
	* @inheritdoc
	*/
	public function import($data, &$object, array $contexts = [])
	{
		$this->_import($data, $object, 
			$context['resource_type'], 
			isset($contexts['quantity']) ? $contexts['quantity'] : 1,
			isset($contexts['include_source_object_item']) ? $contexts['include_source_object_item'] : false
		);
	}
	
	private function _import(
		ExchangeRecipePlanItemInterface $exchangeRecipePlanItem, 
		ExchangePlanItemInterface &$exchangePlanItem,
		ResourceTypeInterface $resourceType,
		int $quantity,
		$includeSourceObjectItem
	)
	{
		$exchangePlanItem = $this->converter->convert(
			$exchangeRecipePlanItem, 
			$exchangePlanItem,
			array(
				'resource_type' => $resourceType, 
				'quantity' => $quantity,
				'include_source_object_item' => $includeSourceObjectItem
			)
		);
		
		$exchangePlanItem
			->setTimes(array(
				'start_date' => $orderItem->getOrder()->getTime('start_date')
				'due_date' => $orderItem->getOrder()->getTime('due_date')
			))
		;
	}
	
	/**
	 * Returns object item source
	 * 
	 * @return ObjectItemSourceInterface
	 */
	protected function queryObjectItemSource()
	{
		
	}
}
