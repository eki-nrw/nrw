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
use Eki\NRW\Component\Processing\Process\Type\ProcessTypeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessRecipePlan extends RecipePlan implements ProcessRecipePlanInterface
{
	/**
	* @inheritdoc
	*/
	protected function matchPlanType(PlanTypeInterface $planType)
	{
		parent::matchPlanType($planType);
		
		if (!$planType->is('process'))
			throw new \InvalidArgumentException("Process Recipe Plan must be process recipe plan type.");
	}

	/**
	* @inheritdoc
	*/
	public function setProcessType(ProcessTypeInterface $processType = null)
	{
		$this->setSolution($processType);		
	}

	/**
	* Returns process type
	* 
	* @return ProcessTypeInterface
	*/	
	public function getProcessType()
	{
		return $this->getSolution();
	}

	/**
	* @inheritdoc
	*/
	public function setSolution($solution)
	{
		if ($solution != null and !$solution instanceof ProcessTypeInterface)
			throw new InvalidArgumentException(sprintf(
				'Solution must be instance of %s',
				ProcessTypeInterface::class
			));

		parent::setSolution($solution);		
	}
}
