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

use Eki\NRW\Component\Locating\Location\LocationInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Location extends Locating
{
	public function __construct($type = '', $name = '', $code = null)
	{
		parent::__construct(Constants::LOCATING_TYPE_LOCATION, $type, $name, $code);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function atLocation(LocationInterface $location)
	{
		throw new \LogicException("Never call this method because of no applying.");
	}

	public function join(LocationInterface $location)
	{
		$this->addObj($location);
	}

	public function leave(LocationInterface $location)
	{
		$this->removeObj($location);
	}
}
