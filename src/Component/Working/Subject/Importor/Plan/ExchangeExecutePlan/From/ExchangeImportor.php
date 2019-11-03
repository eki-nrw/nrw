<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\Plan\ExchangeExecutePlan\From;

use Eki\NRW\Component\Working\Subject\Importor\Plan\ExchangeExecutePlan\BaseImportor;
use Eki\NRW\Component\Working\Exchanging\ExchangeInterface;

use Eki\NRW\Common\Compose\ObjectItem\ObjectItem;
use Eki\NRW\Common\Compose\ObjectItemSource\ObjectItemSource;
use Eki\NRW\Common\QuantityValue\QuantityValue;

use Eki\NRW\Component\Working\Plan\ExchangeRecipePlanInterface;

use Eki\NRW\Mdl\Working\Subject\DirectorInterface;
use Eki\NRW\Component\Working\Plan\ExchangeExecutePlanInterface;
use Eki\NRW\NRW\Boundary\Outgoing\Demand\CustomerOrderInterface;
use Eki\NRW\NRW\Base\Methods;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeImportor extends BaseImportor
{
	/**
	* Checks if it can be imported
	* 
	* @param mixed $data
	* @param object $object
	* 
	* @return bool
	*/
	public function supportData($data)
	{
		return $data instanceof ExchangeInterface;
	}
	
	/**
	* @inheritdoc
	*/
	public function import($data, &$object, array $contexts = [])
	{
		$this->_import($data, $object, $contexts);
	}

	private function _import(ExchangeInterface $exchange, ExchangeExecutePlanInterface &$plan, array $contexts = [])
	{
		// participants/partners of Exchange
		foreach($exchange->getParticipants() as $participant)
		{
			$plan->setResponsibility(new Responsibility($participant->getRole(), $participant->getPartner()->getActor()));
		}
		
		// 
		
		// timing
		
		// properties
		
	}
}
