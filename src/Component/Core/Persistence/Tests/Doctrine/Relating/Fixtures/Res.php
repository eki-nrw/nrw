<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Relating\Fixtures;

use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Res\Model\ResTrait;

use \stdClass;

class Res extends stdClass implements ResInterface
{
	use ResTrait;
	
	public function setId($id)
	{
		$this->id = $id;
	}
}
