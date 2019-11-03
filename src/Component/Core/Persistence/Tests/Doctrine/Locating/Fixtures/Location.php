<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Locating\Fixtures;

use Eki\NRW\Component\Locating\Location\Location as BaseLocation;

class Location extends BaseLocation
{
	public function setId($id)
	{
		$this->id = $id;
	}
}
