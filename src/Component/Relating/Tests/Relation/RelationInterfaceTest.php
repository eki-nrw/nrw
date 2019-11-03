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

use Eki\NRW\Component\Relating\Relation\RelationInterface;
use Eki\NRW\Common\Relations\RelationInterface as BaseRelationInterface;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Timing\StartEndTimeInterface;
use Eki\NRW\Common\Relations\TypeMeaningInterface;
use Eki\NRW\Common\Relations\PotentialInterface;

use PHPUnit\Framework\TestCase;

class RelationInterfaceTest extends TestCase
{
	public function testInterfaces()
	{
		$relation = $this->getMockBuilder(RelationInterface::class)
			->getMockForAbstractClass()
		;
		
		$this->assertInstanceOf(BaseRelationInterface::class, $relation);
		$this->assertInstanceOf(ResInterface::class, $relation);
		$this->assertInstanceOf(StartEndTimeInterface::class, $relation);
		$this->assertInstanceOf(PotentialInterface::class, $relation);
	}
}
