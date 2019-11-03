<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Relating\Fixtures;

use Eki\NRW\Component\Core\Persistence\Doctrine\Relating\Relation as BaseRelation;

class Relation extends BaseRelation
{
	public function setId($id)
	{
		$this->id = $id;
	}	
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
}
