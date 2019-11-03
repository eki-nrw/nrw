<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\Activity\ProcessActivity\From;

use Eki\NRW\Component\Working\Subject\Importor\Activity\ProcessActivity\BaseImportor;
use Eki\NRW\Component\Working\PlanItem\ProcessExecutePlanItemInterface;
use Eki\NRW\Component\Working\Activity\ProcessActivityInterface;

use Eki\NRW\Common\Compose\ObjectItem\ObjectItem;
use Eki\NRW\Common\QuantityValue\QuantityValue;

use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;
use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessExecutePlanItemImportor extends BaseImportor
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
			
		if ($data instanceof ProcessExecutePlanItemInterface)
			return true;
	}
	
	/**
	* @inheritdoc
	*/
	public function import($data, &$object, array $contexts = [])
	{
		$this->_import($data, $object, $this->getObjectItem_Item($contexts), $this->getWorker($contexts));
	}
	
	private function getObjectItem_Item(array $contexts)
	{
		if (isset($contexts['object_item_item']))
			return $contexts['object_item_item'];	
	}
	
	private function getWorker(array $contexts)
	{
		if (isset($contexts['worker']))
			return $contexts['worker'];
	}
	
	private function _import(
		ProcessExecutePlanItemInterface $processExecutePlanItem, 
		ProcessActivityInterface &$processActity,
		ResourceInterface $resource,
		ResourceInterface $worker
	)
	{
		$processActivity->setObjectItem((new ObjectItem())
			->setItem($resource)
			->setQuantityValue($processExecutePlanItem->getObjectItem()->getQuantityValue())
			->setSepecicifcations($processExecutePlanItem->getObjectItem()->getSpecifications())
		);
		
		$processActivity->setResponsibility(array(
			'worker' => $worker
		));
		
		$processActity->setProcess($processExecutePlanItem->getProcess());
		
		return $processActity;
	}
}
