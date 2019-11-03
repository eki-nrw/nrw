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
interface ExchangeAwareInterface
{
	/**
	* Sets/Resets exchange
	* 
	* @param ExchangeInterface|null $exchange
	* 
	* @return void
	*/
	public function setExchange(ExchangeInterface $exchange = null);
}
