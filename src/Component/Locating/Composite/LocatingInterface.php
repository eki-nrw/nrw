<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Locating\Composite;

use Eki\NRW\Component\Relating\Relation\GroupInterface;
use Eki\NRW\Component\Locating\Location\LocationInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface LocatingInterface extends GroupInterface
{
	/**
	* Determine the given location so subjects locate at it.
	* 
	* @param LocationInterface $location
	* 
	* @return void
	*/
	public function atLocation(LocationInterface $location);
}
