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

use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\Base\Engine\Transacting;

/**
 * Abstract Service
 * 
 * + Service is of main interface of the engine
 * + Service has settings portion
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
abstract class AbstractService implements Transacting
{
	/**
	* @var \Eki\NRW\Component\Base\Engine\Engine;
	*/
	protected $engine;

	/**
	* @var array
	*/
	protected $settings;
	
	public function __construct(
		Engine $engine,
		array $settings
	)
	{
		$this->engine = $engine;
		$this->settings = $settings;
	}

	/**
	* Gets a portion of settings by index 
	* 
	* @param string $index
	* 
	* @return array
	*/
	protected function getSettings($index = null)
	{
		if ($index === null)
			return $this->settings;
		else
			return $this->settings[$index];
	}
	
	/**
	* @inheritdoc
	*/
	public function beginTransaction()
	{
		$this->engine->beginTransaction();
	}
	
	/**
	* @inheritdoc
	*/
	public function commit()
	{
		$this->engine->commit();
	}
	
	/**
	* @inheritdoc
	*/
	public function rollback()
	{
		$this->engine->rollback();
	}
}
