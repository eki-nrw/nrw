<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\Plan;

use Eki\NRW\Mdl\Working\Subject\AbstractImportor;

use Eki\NRW\Mdl\Working\PlanItemInterface;
use Eki\NRW\Mdl\Working\Recipe\AttainmentInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeRecipeToExchangeExecutePlanImportor extends AbstractImportor
{
	/**
	* @var AttainmentInterface
	*/
	protected $attainment;

	public function setAttainment(AttainmentInterface $attainment)
	{
		$this->attainment = $attainment;		
	}
	
	/**
	* Checks if it can be imported
	* 
	* @param mixed $data Data imports to object
	* @param object $object Object is imported from data
	* 
	* @return bool
	*/
	public function support($data, $object)
	{
		if ($data instanceof PlanInterface and $object instanceof PlanInterface)
		{
			$recipePlan = $data;
			$executePlan = $object;
			
			if (null === ($recipePlanType = $recipePlan->getPlanType()))
				return false;
			if (null === ($executePlanType = $executePlan->getPlanType()))
				return false;
				
			if (!$recipePlanType->is('recipe'))
				return false;
			if (!$executePlanType->is('execute'))
				return false;
				
			if (false === $this->attainment->support($recipePlan, $executePlan))	
				return false;
				
			return true;
		}
		
		return false;
	}
	
	/**
	* @inheritdoc
	*/
	public function import($data, &$object, array $contexts = [])
	{
		if (!$this->support($data, $object))
			throw new \UnexpectedValueException(sprintf("Data and/or Object are not supported."));
		
		$this->_import($data, $object, $contexts);
	}
	
	protected function _import(PlanInterface $recipePlan, PlanInterface &$executePlan, array $contexts)
	{
		$this->attainment->doAttainment($recipePlan, $executePlan, $contexts);
		
		// notify
	}
}
