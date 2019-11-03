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

use Eki\NRW\Common\Location\LocationInterface as BaseLocationInterface;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Res\Model\TimestampableInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface LocationInterface extends 
	BaseLocationInterface,
	ResInterface,
	TimestampableInterface
{
	/**
	* Returns the code of the location
	* 
	* @return string
	*/
	public function getCode();
	
	/**
	* Sét the code of the location
	* 
	* @param string $code
	* 
	* @return void
	*/
	public function setCode($code);
}
