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

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

/**
 * System tools
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class SystemTools
{
	private $tools = [];
	
	/**
	* Register a sustem tool
	* 
	* @param object $tool
	* @param string $name
	* 
	* @return void
	* 
	* @throw
	* 
	*/
	public function registerTool($tool, $name)
	{
		if (!is_object($tool))
			throw new InvalidArgumentException("tool", "Tool is not object.");
			
		if (isset($this->tools[$name]))
			throw new InvalidArgumentException("name", "Tool of namee '$name' already exists.");
			
		$this->tools[$name] = $tool;
	}
	
	public function getTool($name)
	{
		if (!isset($this->tools[$name]))
			throw new InvalidArgumentException("name", "No tool of name '$name'.");

		return $this->tools[$name];
	}
}
