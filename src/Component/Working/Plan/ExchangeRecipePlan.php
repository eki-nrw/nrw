<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Plan;

use Eki\NRW\Mdl\Working\PlanTypeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeRecipePlan extends RecipePlan implements ExchangeRecipePlanInterface
{
	/**
	* @inheritdoc
	*/
	protected function matchPlanType(PlanTypeInterface $planType)
	{
		parent::matchPlanType($planType);
		
		if (!$planType->is('exchange'))
			throw new \InvalidArgumentException("Exchange Recipe Plan must be exchange recipe plan type.");
	}

	/**
	* @inheritdoc
	*/
	public function setExchangeType(ExchangeTypeInterface $exchangeType = null)
	{
		$this->setSolution($exchangeType);		
	}

	/**
	* Returns exchange type
	* 
	* @return ExchangeTypeInterface
	*/	
	public function getExchangeType()
	{
		return $this->getSolution();
	}

	/**
	* @inheritdoc
	*/
	public function setSolution($solution)
	{
		if ($solution != null and !$solution instanceof ExchangeTypeInterface)
			throw new InvalidArgumentException(sprintf(
				'Solution must be instance of %s',
				ExchangeTypeInterface::class
			));

		parent::setSolution($solution);		
	}
}
