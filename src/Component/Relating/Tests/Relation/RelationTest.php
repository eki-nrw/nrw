<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Relating\Tests\Relation;

use Eki\NRW\Component\Relating\Relation\Relation;
use Eki\NRW\Component\Relating\Relation\RelationInterface;

use PHPUnit\Framework\TestCase;

class RelationTest extends TestCase
{
	public function testConstructor()
	{
		$relation = new Relation();
		
		$this->assertInstanceOf(RelationInterface::class, $relation);
	}
}
