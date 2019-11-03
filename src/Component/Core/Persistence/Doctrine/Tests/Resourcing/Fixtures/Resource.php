<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Resourcing\Fixtures;

use Eki\NRW\Component\Resourcing\Resource\Resource as BaseResource;

class Resource extends BaseResource
{
	public function setId($id)
	{
		$this->id = $id;
	}
}
