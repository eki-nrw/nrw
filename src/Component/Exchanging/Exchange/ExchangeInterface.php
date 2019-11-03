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

use Eki\NRW\Mdl\Exchanging\Exchange\ExchangeInterface as BaseExchangeInterface;
use Eki\NRW\Common\Res\Model\ResInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ExchangeInterface extends 
	BaseExchangeInterface,
	ResInterface
{
}
