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

use Eki\NRW\Component\Relating\Relation\Group;
use Eki\NRW\Component\Relating\Relation\GroupInterface;

use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
	public function testConstructor()
	{
		$group = new Group();
		
		$this->assertInstanceOf(GroupInterface::class, $group);
	}
}
