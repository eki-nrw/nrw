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

use Eki\NRW\Component\Working\AbstractConverter;
use Eki\NRW\Component\Working\PlanItem\ExchangeRecipePlanItemInterface;
use Eki\NRW\Component\Working\PlanItem\ExchangePlanItemInterface;
use Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface;
use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;
use Eki\NRW\Mdl\Working\ObjectBuilderInterface;
use Eki\NRW\Common\Context\ContextInterface;
use Eki\NRW\Common\Context\HasContextInterface;
use Eki\NRW\Common\Context\HasContextTrait;

use \ReflectionClass;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class RecipeToExecutePlanItemConverter extends AbstractConverter implements HasContextInterface
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
	* @var string
	*/
	protected $executePlanItemType;
	
	/**
	* @var string
	*/
	protected $recipePlanClass;

	/**
	* @var string
	*/
	protected $planClass;

	/**
	* Constructor
	* 
	* @param string $executePlanItemType
	* @param string $recipePlanClass
	* @param string $planClass
	*/	
	public function __construct($executePlanItemType, $recipePlanClass, $planClass)
	{
		$recipeReflection = new ReflectionClass($recipePlanClass);
		if (!$recipeReflection->implementsInterface('\Eki\NRW\Component\Working\PlanItem\ExecuteRecipePlanItemInterface'))
			throw new \InvalidArgumentException("recipeClass parameter must inherit from '\Eki\NRW\Component\Working\PlanItem\ExecuteRecipePlanItemInterface'");
		$reflection = new ReflectionClass($planClass);
		if (!$reflection->implementsInterface('\Eki\NRW\Component\Working\PlanItem\ExecutePlanItemInterface'))
			throw new \InvalidArgumentException("recipeClass parameter must inherit from '\Eki\NRW\Component\Working\PlanItem\ExecutePlanItemInterface'");
			
		$this->executePlanItemType = $executePlanItemType;
		$this->recipePlanClass = $recipePlanClass;
		$this->planClass = $planClass;
	}
	
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
			$object instanceof $this->recipePlanClass
			and
			$result != null and $result instanceof $this->planClass
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
	* @param ExecuteRecipePlanItemInterface $recipePlanItem
	* @param ExecutePlanItemInterface $planItem
	* @param ResourceTypeInterface $resourceType
	* @param int $quantity
	* 
	* @return ExecutePlanItemInterface
	*/	
	protected function _convert(
		ExecuteRecipePlanItemInterface $recipePlanItem, 
		ExecutePlanItemInterface $planItem, 
		ResourceTypeInterface $resourceType,
		int $quantity,
		$includeSourceObjectItem
	)
	{
		if (null === $planItem)
		{
			$this->builder
				->setObjectType($this->executePlanItemType)
			;
		}
		else
		{
			$this->builder
				->setObject($planItem)
			;
		}
		
		$recipeObjectItem = $recipePlanItem->attain(
			(new ObjectItem())
				->setItem($resourceType)
				->setQuantityValue(new QuantityValue($quantity, $resourceType->getDefaultUnitAlias()))
		);
		
		$planItem = $this->builder
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
			$planItem = $this->builder
				->setObjectItemSource($this->queryObjectItemSourceFromRecipe($recipePlanItem))
			;
		}
				
		$planItem = $this->builder
			->get()
		;

		return $planItem;
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
