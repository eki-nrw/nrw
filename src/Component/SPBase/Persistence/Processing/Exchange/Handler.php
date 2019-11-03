<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Processing\Exchange;

use Eki\NRW\Component\Processing\Exchange\ExchangeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create a new exchange of type $identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Processing\Exchange\ExchangeInterface
	* @throws
	*/
	public function create($identifier);
	
	/**
	* Load exchange object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Processing\Exchange\ExchangeInterface
	*/
	public function load($id);
	
	/**
	* Delete given exchange
	* 
	* @param \Eki\NRW\Component\Processing\Exchange\ExchangeInterface $exchange
	* 
	* @return void
	*/	
	public function delete(ExchangeInterface $exchange);
	
	/**
	* Update a exchange identified by $id
	* 
	* @param \Eki\NRW\Component\Processing\Exchange\ExchangeeInterface $exchange
	* 
	* @return void
	*/
	public function update(ExchangeInterface $exchange);
}
