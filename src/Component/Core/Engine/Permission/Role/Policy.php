<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Permission\Role;

use Eki\NRW\Component\Base\Engine\Permission\Role\Policy as BasePolicy;
use Eki\NRW\Component\Base\Engine\Permission\Role\Role;

/**
 */
class Policy extends BasePolicy
{
    /**
     * @var Role
     */
    protected $role;

	/**
	* @var \Eki\NRW\Component\Core\Engine\Permission\Role[]
	*/
    protected $limitations = [];

	public function __construct($service, $function, array $limitations = array())
	{
		$this->service = $service;
		$this->function = $function;

		foreach($limitations as $limitation)
		{
			$this->addLimitation($limitation);
		}
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getService()
	{
		return $this->service;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getFunction()
	{
		return $this->function;
	}

	public function addLimitation(Limitation $limitation)
	{
		$this->limitations[] = $limitation;
	}

	/**
	* @inheritdoc
	* 
	*/
    public function getLimitations()
    {
		return $this->limitations;
	}
}
