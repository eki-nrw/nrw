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

use Eki\NRW\Component\Relating\Relation\RelationshipInterface;
use Eki\NRW\Common\Relations\Relationship\RelationshipInterface as BaseRelationshipInterface;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Timing\StartEndTimeInterface;
use Eki\NRW\Common\Relations\TypeMeaningInterface;
use Eki\NRW\Common\Relations\PotentialInterface;

use PHPUnit\Framework\TestCase;

class RelationshipInterfaceTest extends TestCase
{
	public function testInterfaces()
	{
		$relationship = $this->getMockBuilder(RelationshipInterface::class)
			->getMockForAbstractClass()
		;
		
		$this->assertInstanceOf(BaseRelationshipInterface::class, $relationship);
		$this->assertInstanceOf(ResInterface::class, $relationship);
		$this->assertInstanceOf(StartEndTimeInterface::class, $relationship);
		$this->assertInstanceOf(PotentialInterface::class, $relationship);
	}
}
