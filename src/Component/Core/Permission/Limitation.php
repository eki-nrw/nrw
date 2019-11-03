<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Permission;

use Eki\NRW\Component\Base\Engine\Permission\Limitation as BaseLimitation;

/**
 * Limitation object 
 */
class Limitation extends BaseLimitation
{
	/**
	* @var string
	*/
	protected $identifier;
	
	public function __construct($identifier, array $limitationValues = array())
	{
		$this->identifier = $identifier;
		$this->limitationValues = $limitationValues;
	}
	
	/**
	* @inheritdoc
	* 
	*/
    public function getIdentifier()
    {
		return $this->identifier;
	}

	/**
	* Gets limitations
	* 
	* @return mixed[]
	*/
    public function getLimitationValues()
    {
		return $this->limitationValues;
	}
}
