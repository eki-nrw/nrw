<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Locating;

use Eki\NRW\Component\Locating\Location\LocationInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create a new location entity in a storage engine
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Locating\Location\LocationInterface
	*/
	public function create($identifier);
	
	/**
	* Load the given location
	* 
	* @param mixed $id
	* 
	* @return \Eki\NRW\Component\Locating\Location\LocationInterface
	*/
	public function load($id);

	/**
	* Delete the given location
	* 
	* @param \Eki\NRW\Component\Locating\Location\LocationInterface $location
	* 
	* @return void
	*/	
	public function delete(LocationInterface $location);
	
	/**
	* Update the given location
	* 
	* @param \Eki\NRW\Component\Locating\Location\LocationInterface $location
	* 
	* @return void
	*/
	public function update(LocationInterface $location);
}
