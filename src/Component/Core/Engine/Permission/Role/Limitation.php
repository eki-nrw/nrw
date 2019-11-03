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

use Eki\NRW\Component\Base\Permission\Role\Limitation as BaseLimitation;

/**
 */
class Limitation extends BaseLimitation
{
	/**
	* @var string
	*/
	protected $identifier;
	
	/**
	* @inheritdoc
	* 
	*/
    public function getIdentifier()
    {
		return $this->identifier;
	}

	/**
	* @inheritdoc
	* 
	*/
    public function getLimitationsValues()
    {
		return $this->limitationValues;
	}
}
