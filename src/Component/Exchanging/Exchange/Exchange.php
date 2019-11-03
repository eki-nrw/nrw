<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Exchanging\Exchange;

use Eki\NRW\Mdl\Exchanging\Exchange\AbstractExchange;
use Eki\NRW\Common\Res\Model\ResTrait;

use Eki\NRW\Component\Networking\Agent\AgentInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Exchange extends AbstractExchange implements ExchangeInterface
{
	use
		ResTrait
	;
	
	/**
	* @inheritdoc
	*/
	protected function matchExchangeType(ExchangeTypeInterface $exchangeType)
	{
		parent::matchExchangeType($exchangeType);
	}
}
