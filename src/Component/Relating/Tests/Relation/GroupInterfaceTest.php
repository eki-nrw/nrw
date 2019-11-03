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

use Eki\NRW\Component\Relating\Relation\GroupInterface;
use Eki\NRW\Common\Relations\Group\GroupInterface as BaseGroupInterface;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Timing\StartEndTimeInterface;
use Eki\NRW\Common\Relations\TypeMeaningInterface;
use Eki\NRW\Common\Relations\PotentialInterface;

use PHPUnit\Framework\TestCase;

class GroupInterfaceTest extends TestCase
{
	public function testInterfaces()
	{
		$group = $this->getMockBuilder(GroupInterface::class)
			->getMockForAbstractClass()
		;
		
		$this->assertInstanceOf(BaseGroupInterface::class, $group);
		$this->assertInstanceOf(ResInterface::class, $group);
		$this->assertInstanceOf(StartEndTimeInterface::class, $group);
		$this->assertInstanceOf(PotentialInterface::class, $group);
	}
}
