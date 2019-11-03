<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence;

/**
* Persistence Handler interface 
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Returns persistence handler of networking
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Networking\Handler
	*/
	public function networkingHandler();

	/**
	* Returns persistence handler of resourcing
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Resourcing\Handler
	*/
	public function resourcingHandler();
	
	/**
	* Returns persistence handler of relations
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Relating\Handler
	*/
	public function relatingHandler();

	/**
	* Returns permission handler
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Handler
	*/
	public function permissionHandler();

	/**
	* Returns transaction handler
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\TransactionHandler
	*/
	public function transactionHandler();
}
