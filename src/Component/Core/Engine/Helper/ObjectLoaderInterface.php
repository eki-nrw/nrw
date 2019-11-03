<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Helper;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
interface ObjectLoaderInterface
{
	/**
	* Checks if the loader supports argument
	* 
	* @param mixed $argument
	* 
	* @return bool
	*/
	public function support($argument);
	
	/**
	* Load object
	* 
	* @param mixed $argument
	* 
	* @return object|null
	* 
	* @throw \UnexpectedValueException
	*/
	public function loadObject($argument);
}
