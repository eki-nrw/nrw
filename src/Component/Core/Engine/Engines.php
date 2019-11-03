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

use Eki\NRW\Component\Base\Engine\Engines as EnginesInterface;
use Eki\NRW\Component\Base\Engine\Engine as EngineInterface;
use Eki\NRW\Component\Base\Engine\Plugins;
use Eki\NRW\Component\SPBase\Persistence\Handler as PersistenceHandler;

use Eki\NRW\Component\Core\Engine\Engine;

use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException as BaseInvalidArgumentException;
use Eki\NRW\Component\Base\Core\Exceptions\InvalidArgumentException;

/**
 * Engines implementation
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class Engines implements EnginesInterface
{
	/**
	* @var \Eki\NRW\Component\Base\Engine\Engine[]
	*/	
	private $engines = [];
	
	/**
	* @var array
	*/
	private $configurations;
	
	/**
	* @var \Eki\NRW\Component\Base\Engine\Plugins
	*/
	private $plugins;

	public function __construct(
		array $configurations,
		Plugins $plugins
	)
	{
		$this->configurations = $configurations;
		$this->plugins = $plugins;
	}
	
	/**
	* @inheritdoc
	*/
	public function init()
	{
		// config
		// setup
		// launch
	}
	
	/**
	* @inheritdoc
	*/
	public function destroy()
	{
		foreach($this->engines as $engineName => $engine)
		{
			if ($engine instanceof EngineInterface)
			{
				// destroy engine
			}	
		}	
	}

	/**
	* Returns engine name list
	* 
	* @return string[]
	*/
	public function getEngineNames()
	{
		return array_keys($this->configurations['engines']);
	}

	/**
	* @inheritdoc
	*/
	public static function getDefaultEngine()
	{
		return $this->getEngine('default');
	}
	
	/**
	* @inheritdoc
	*/
	public function getEngine($engineName = null, array $settings = [])
	{
		if ($engineName === null)
			return $this->getDefaultEngine();
			
		if (isset($this->engines[$engineName]))
			return $this->engines[$engineName];

		if (!isset($this->configurations['engines'][$engineName]))
			throw new InvalidArgumentException("engineName", "No engine name '$engineName' is configured.");

		$settings = $settings + $this->getEngineSettings($engineName);

		$engine = new Engine(
			$engineName,
			$settings,
			$persistenceHandlers,
			$this->getPlugins()
		);
		
		return $engine;
	}
	
	protected function getEngineSettings($engineNames)
	{
		return $this->configurations['engines'][$engineName];
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function getPlugins()
	{
		return $this->plugins;
	}
}
