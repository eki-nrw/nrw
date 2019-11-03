<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Locating\Location;

use Eki\NRW\Common\Location\Location as BaseLocation;
use Eki\NRW\Common\Res\Model\ResTrait;
use Eki\NRW\Common\Res\Model\TimestampableTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Location extends BaseLocation implements LocationInterface
{
	use
		ResTrait,
		TimestampableTrait
	;

	/**
	* Returns the code of the location
	* 
	* @return string
	*/
	public function getCode()
	{
		if ($this->hasCoordinate('code'))
			return $this->getCoordinate('code');	
	}
	
	/**
	* Sét the code of the location
	* 
	* @param string $code
	* 
	* @return void
	*/
	public function setCode($code)
	{
		$this->setCoordinate('code', (string)$code);
	}
}
