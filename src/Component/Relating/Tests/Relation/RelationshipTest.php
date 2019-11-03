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

use Eki\NRW\Component\Relating\Relation\Relationship;
use Eki\NRW\Component\Relating\Relation\RelationshipInterface;

use PHPUnit\Framework\TestCase;

class RelationshipTest extends TestCase
{
	public function testConstructor()
	{
		$relationship = new Relationship();
		
		$this->assertInstanceOf(RelationshipInterface::class, $relationship);
	}
}
