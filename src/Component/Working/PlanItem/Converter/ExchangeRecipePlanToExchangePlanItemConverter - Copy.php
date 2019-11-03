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

use Eki\NRW\Component\Working\ConverterInterface;
use Eki\NRW\Component\Working\AbstractConverter;
use Eki\NRW\Component\Working\PlanItem\ExchangeRecipePlanItemInterface;
use Eki\NRW\Component\Working\PlanItem\ExchangePlanItemInterface;
use Eki\NRW\NRW\Base\Resource\Resource\ResourceTypeInterface;
use Eki\NRW\NRW\Base\Network\Resource\ResourceInterface;
use Eki\NRW\Mdl\Working\ObjectBuilderInterface;
use Eki\NRW\Common\Context\ContextInterface;
use Eki\NRW\Common\Context\HasContextInterface;
use Eki\NRW\Common\Context\HasContextTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeRecipeToExchangePlanItemConverter extends AbstractConverter implements HasContextInterface
{
	use 
		HasContextTrait
	;
	
	/**
	* @var ObjectBuilderInterface
	*/
	protected $builder;
	
	/**
	* @var ConverterInterface
	*/
	protected $converterForSource;
	
	/**
	* Sets builder to build exchange plan item
	* 
	* @param ObjectBuilderInterface $builder
	* 
	* @return void
	*/
	public function setBuilder(ObjectBuilderInterface $builder)
	{
		$this->builder = $builder;
	}
	
	/**
	* @inheritdoc
	*/
	public function convert($object, $result = null, array $options = [])
	{
		$options = array_replace($options, array(
			'resource_type' => $this->_getParameter('resource_type', $options),
			'quantity' => $this->_getParameter('quantity', $options, 0),
			'include_source_object_item' => $this->_getParameter('include_source_object_item', $options, false),
		));
		$this->validateOptions($options);
		
		return $this->_convert($object, $result, 
			$options['resource_type'], $options['quantity'], $options['include_source_object_item']
		);
	}
	
	private function _getParameter($paramName, array $options, $default = null)
	{
		if (!isset($options[$paramName]))
		{
			if (null !== $this->getContext())
			{
				return 
					$this->getContext()->hasParameter($paramName) ?
					$this->getContext()->getParameter($paramName) :
					$default
				;
			}
		}
		else
		{
			return $options[$paramName];
		}
	}
	
	/**
	* @inheritdoc
	*/
	public function support($object, $result = null)
	{
		return 
			$object instanceof ExchangeRecipePlanItemInterface
			and
			$result != null and $result instanceof ExchangePlanItemInterface
			or
			$result === null
		;
	}
	
	/**
	* @inheritdoc
	*/
	protected function validateOptions(array $options)
	{
		if (!isset($options['resource_type']) or 
			!isset($contexts['quantity']) or 
			!isset($contexts['include_source_object_item'])
		)
			throw new \InvalidArgumentException("Contexts parameters must have index 'resource_type', 'quantity' and 'include_source_object_item'.");
	}

	/**
	* Convert
	* 
	* @param ExchangeRecipePlanItemInterface $recipePlanItem
	* @param ExchangePlanItemInterface $exchangePlanItem
	* @param ResourceTypeInterface $resourceType
	* @param int $quantity
	* 
	* @return
	*/	
	protected function _convert(
		ExchangeRecipePlanItemInterface $recipePlanItem, 
		ExchangePlanItemInterface $exchangePlanItem, 
		ResourceTypeInterface $resourceType,
		int $quantity,
		$includeSourceObjectItem
	)
	{
		if (null === $exchangePlanItem)
		{
			$this->builder
				->setObjectType('plan_item.execute.exchange')
			;
		}
		else
		{
			$this->builder
				->setObject($exchangePlanItem)
			;
		}
		
		$recipeObjectItem = $recipePlanItem->attain(
			(new ObjectItem())
				->setItem($resourceType)
				->setQuantityValue(new QuantityValue($quantity, $resourceType->getDefaultUnitAlias()))
		);
		
		$exchangePlanItem = $this->builder
			->setObjectItem(
				(new ObjectItem())
					->setItem($recipeObjectItem->getItem())
					->setQuantityValue(
						new QuantityValue(
							$recipeObjectItem->getQuantityValue()->getQuantity(), 
							$recipeObjectItem->getQuantityValue()->getUnitAlias()
						)
					)
					->setSpecifications($recipeObjectItem->getSpecifications())
			)
		;

		if ($includeSourceObjectItem)
		{
			$exchangePlanItem = $this->builder
				->setObjectItemSource($this->queryObjectItemSourceFromRecipe($recipePlanItem))
			;
		}
				
		$exchangePlanItem = $this->builder
			->get()
		;

		return $exchangePlanItem;
	}
	
	/**
	* Gets Object Item Source for Exchange Plan Item from Recipe
	* 
	* @param ExchangeRecipePlanItemInterface $recipePlanItem
	* 
	* @return ObjectItemSourceInterface
	*/
	protected function queryObjectItemSourceFromRecipe(ExchangeRecipePlanItemInterface $recipePlanItem)
	{
		return null;  // dirty
	}
}
