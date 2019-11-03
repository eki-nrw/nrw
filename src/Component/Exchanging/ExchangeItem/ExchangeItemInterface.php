<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Exchanging\ExchangeItem;

use Eki\NRW\Mdl\Exchanging\ExchangeItem\ExchangeItemInterface as BaseExchangeItemInterface;
use Eki\NRW\Common\Res\Model\ResInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ExchangeItemInterface extends 
	BaseExchangeItemInterface,
	ResInterface
{
}
