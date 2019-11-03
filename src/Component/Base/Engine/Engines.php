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

use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Engines
{
	/**
	* Returns engine name list
	* 
	* @return string[]
	*/
	public function getEngineNames();

	/**
	* Gets default engine
	* 
	* @return \Eki\NRW\Component\Base\Engine\Engine
	*/	
	public static function getDefaultEngine();
	
	/**
	* Gets an engine with engine name. Launch it if not launched
	* 
	* @param string|null $engineName Null for the default engine
	* 
	* @return \Eki\NRW\Component\Base\Engine\Engine
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	*/
	public function getEngine($engineName = null, array $settings = []);
	
	/**
	* Returns system plug-ins
	* 
	* @return \Eki\NRW\Component\Base\Engine\Plugins
	*/
	public function getPlugins();
}
