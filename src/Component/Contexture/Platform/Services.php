<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Contexture\Platform;

/**
* Platform services interface 
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
interface Services
{
	/**
	* Returns the service of name $name
	* 
	* @param string $name
	* 
	* @throw
	* 
	* @return object
	*/
	public function get($name);
	
	/**
	* Checks if there is a tool with name $name 
	* 
	* @param string $name
	* 
	* @return bool
	*/
	public function has($name);
}
