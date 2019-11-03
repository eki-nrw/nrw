<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Cache;

/**
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
interface CacheInterface
{
	/**
	* Set object to cache
	* 
	* @param object $obj
	* @param string|null $info Null if default.
	* 
	* @return void
	*/
	public function set($obj, $info = null);
	
	/**
	* Get object from cache by reference (default is id)
	* 
	* @param mixed $objectInfo It relates to $info when setting
	* @param string|null $ref Reference string of object class. Null if default.
	* 
	* @return object|null
	* 
	*/
	public function get($objectInfo, $ref = null);
	
	/**
	* Clear cache of object
	* 
	* @param object $obj
	* @param string|null $info Default if null
	* 
	* @return void
	*/
	public function clear($obj, $info = null);
}
