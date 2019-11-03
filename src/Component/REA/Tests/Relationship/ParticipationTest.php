<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Relationship;

use Eki\NRW\Mdl\REA\Relationship\ParticipationInterface;
use Eki\NRW\Mdl\REA\Relationship\Participation;
use Eki\NRW\Mdl\REA\Relationship\RelationshipInterface;
use Eki\NRW\Mdl\REA\Agent\AgentInterface;
use Eki\NRW\Mdl\REA\Event\EventInterface;
use Eki\NRW\Mdl\REA\Relationship\Constants as RelationshipConstants;

use PHPUnit\Framework\TestCase;

class ParticipationTest extends TestCase
{
    public function testInterfaces()
    {
    	$participation = $this->getMockBuilder(Participation::class)
			->disableOriginalConstructor()
    		->getMock()
    	;

		$this->assertInstanceOf(RelationshipInterface::class, $participation);
		$this->assertInstanceOf(ParticipationInterface::class, $participation);
    }

    public function testChainTypes()
    {
    	$participation = new Participation('an_participation_type');

		$this->assertSame(RelationshipConstants::REA_RELATIONSHIP_PARTICIPATION, $participation->getCategorizationType());
		$this->assertSame('an_participation_type', $participation->getMainType());
    }

    public function testDefaults()
    {
    	$participation = new Participation('an_participation_type');

		$this->assertNull($participation->getAgent());
		$this->assertNull($participation->getEvent());
    }
}
