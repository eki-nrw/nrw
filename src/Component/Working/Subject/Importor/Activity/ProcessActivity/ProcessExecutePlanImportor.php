<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\Activity\ProcessActivity;

use Eki\NRW\Mdl\Working\Subject\ImportorInterface;
use Eki\NRW\Component\Working\Plan\ProcessExecutePlan;

use Eki\NRW\Common\Compose\ObjectItem\ObjectItem;
use Eki\NRW\Common\QuantityValue\QuantityValue;

use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;
use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessExecutePlanImportor extends ImportorInterface
{
	/**
	* @inheritdoc
	*/
	public function support($data, $object)
	{
		if ($data instanceof ProcessExecutePlan)
			return true;
			
		return false;
	}
	
	/**
	* @inheritdoc
	*/
	public function import($data, &$object, array $contexts = [])
	{
		$this->_import($data, $object);
	}
	
	private function _import(
		ProcessExecutePlan $processExecutePlan, 
		OutputProcessActivity &$outputProcessActity
	)
	{
		$planObjectItem = $processExecutePlan->getObjectives('default');
		$resourceBuilder = $this->resourceBuilder;
		$resource = $resourceBuilder
			->get()
		;

    	// Build OutputProcessActivity
    	$builder = $this->director->getBuilder('activity.process.output');
    	$activity = $builder
    		->set('process', $plan->getProcess())
    		->set('responsibility', $plan->getResponsibility());
    		->set('object_item', (new ObjectItem())
    			->setItem($resource)
    			->setQuantityValue((new QuantityValue()
    				->setQuantity($planObjectItem->getQuantityValue()->getQuantity())
    				->setUnitAlias($planObjectItem->getQuantityValue()->getUnitAlias())
    			)
    			->setSpecifications($planObjectItem->getSpecifications())
    		))
    		->build()
    	;
	}
}
