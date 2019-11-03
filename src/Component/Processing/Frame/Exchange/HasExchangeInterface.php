<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Processing\Frame\Exchange;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface HasExchangeInterface extends ExchangeAwareInterface
{
	/**
	* Returns exchange
	* 
	* @return ExchangeInterface
	*/
	public function getExchange();
}
