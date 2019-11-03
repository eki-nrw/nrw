<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine;

use Eki\NRW\Component\Base\Engine\Plugins as PluginsInterface;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

/**
 * Plugins implementation.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class Plugins implements PluginsInterface
{
	/**
	* @var object[]
	*/
	private $plugins = [];
	
	/**
	* Gets the plugin by name
	* 
	* @param string $name
	* 
	* @throw \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException 
	* 
	* @return object
	*/
	public function getPlugin($name)
	{
		if (!isset($this->plugins[$name]))
			throw new InvalidArgumentException("name", "No plugin of name '$name'");
			
		return $this->plugins[$name];
	}
	
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
	public function registerPlugin($plugin, $name)
	{
		if (!is_object($plugin))
			throw new InvalidArgumentException("plugin", "Plugin is not object.");
			
		if (isset($this->plugins[$name]))
			throw new InvalidArgumentException("name", "Plugin of name '$name' already exists.");
			
		$this->plugins[$name] = $plugin;
	}
	
	/**
	* Checks if the plugin with name exists or not
	* 
	* @param string $name
	* 
	* @return bool
	*/
	public function hasPlugin($name)
	{
		return isset($this->plugins[$name]);
	}
}
