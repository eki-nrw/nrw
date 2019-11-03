<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Event\Affect\QuantityValue;

use Eki\NRW\Common\QuantityValue\Operator as BaseOperator;
use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
class ResourceOperator extends BaseOperator
{
	/**
	* Add a quantity value to resource subject
	* 
	* @param QuantityValueSubjectInterface $subject
	* @param QuantityValueInterface $quantityValue
	* 
	* @return
	*/
	protected function add(QuantityValueSubjectInterface $subject, QuantityValueInterface $quantityValue)
	{
		$resource = $subject->getSubject();
		$resourceQuantityValue = $resource->getQuantityValue();
		
		$this->_sameUnitAlias('add', $resourceQuantityValue, $quantityValue);
		
		$resourceQuantityValue->setQuantity(
			$resourceQuantityValue->getQuantity() + $quantityValue->getQuantity()
		);
		
		return $subject;
	}
	
	private function _sameUnitAlias(
		$operator,
		QuantityValueInterface $resourceQuantityValue, 
		QuantityValueInterface $quantityValue
	)
	{
		if ($$resourceQuantityValue->getUnitAlias() !== $quantityValue->getUnitAlias())
		{
			throw new \LogicException(
				'Cannot %s two quantity value that have not the same unit alias. ' .
				'The unit alias of resource is %s. The added unit alias is %s.', 
				$operator,
				$resourceQuantityValue->getUnitAlias(),
				$quantityValue->getUnitAlias()
			);
		}
	}

	/**
	* Subtract a quantity value to resource subject
	* 
	* @param QuantityValueSubjectInterface $subject
	* @param QuantityValueInterface $quantityValue
	* 
	* @return
	*/
	protected function subtract(QuantityValueSubjectInterface $subject, QuantityValueInterface $quantityValue)
	{
		$resource = $subject->getSubject();
		$resourceQuantityValue = $resource->getQuantityValue();

		$this->_sameUnitAlias('subtract', $resourceQuantityValue, $quantityValue);
		
		$resourceQuantityValue->setQuantity(
			$resourceQuantityValue->getQuantity() - $quantityValue->getQuantity()
		);
		
		return $subject;
	}

	/**
	* Init a quantity value to new created resource subject
	* 
	* @param QuantityValueSubjectInterface $subject
	* @param QuantityValueInterface $quantityValue
	* 
	* @return
	*/
	protected function init(QuantityValueSubjectInterface $subject, QuantityValueInterface $quantityValue)
	{
		$resource = $subject;
		
		if (null !== ($resourceQuantityValue = $resource->getQuantityValue()) || !$resourceQuantityValue->isEmpty())
			throw new InvalidArgumentException('Cannot initialize a quantity value to a resource that has not-empty quantity value.');
		
		$resourceQuantityValue->setQuantity($quantityValue);
		
		return $subject;
	}

	/**
	* Consume
	* 
	* @param QuantityValueSubjectInterface $subject
	* @param QuantityValueInterface $quantityValue
	* 
	* @return
	*/
	protected function consume(QuantityValueSubjectInterface $subject, QuantityValueInterface $quantityValue)
	{
		return $this->subtract($subject, $quantityValue);
	}

	/**
	* Use
	* 
	* @param QuantityValueSubjectInterface $subject
	* @param QuantityValueInterface $quantityValue
	* 
	* @return
	*/
	protected function use(QuantityValueSubjectInterface $subject, QuantityValueInterface $quantityValue)
	{
		// No affection on quantity
	}

	/**
	* Cite
	* 
	* @param QuantityValueSubjectInterface $subject
	* @param QuantityValueInterface $quantityValue
	* 
	* @return
	*/
	protected function cite(QuantityValueSubjectInterface $subject, QuantityValueInterface $quantityValue)
	{
		// No affection on quantity
	}
	
	/**
	* Accept
	* 
	* @param QuantityValueSubjectInterface $subject
	* @param QuantityValueInterface $quantityValue
	* 
	* @return
	*/
	protected function accept(QuantityValueSubjectInterface $subject, QuantityValueInterface $quantityValue)
	{
		// No affection on quantity
	}
	
	/**
	* Load
	* 
	* @param QuantityValueSubjectInterface $subject
	* @param QuantityValueInterface $quantityValue
	* 
	* @return
	*/
	protected function load(QuantityValueSubjectInterface $subject, QuantityValueInterface $quantityValue)
	{
		// No affection on quantity
	}
	
	/**
	* Work
	* 
	* @param QuantityValueSubjectInterface $subject
	* @param QuantityValueInterface $quantityValue
	* 
	* @return
	*/
	protected function work(QuantityValueSubjectInterface $subject, QuantityValueInterface $quantityValue)
	{
		// No affection on quantity
	}

}
