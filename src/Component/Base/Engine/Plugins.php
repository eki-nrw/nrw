<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine;

/**
 * System plugins interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface Plugins
{
	/**
	* Gets the plugin by name
	* 
	* @param string $name
	* 
	* @throw \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException 
	* 
	* @return object
	*/
	public function getPlugin($name);
	
	/**
	* Register a plugin to the system
	* 
	* @param object $plugin
	* @param string $name
	* 
	* @throw 
	* 
	* @return void
	*/
	public function registerPlugin($plugin, $name);
	
	/**
	* Checks if the plugin with name exists or not
	* 
	* @param string $name
	* 
	* @return bool
	*/
	public function hasPlugin($name);
}
