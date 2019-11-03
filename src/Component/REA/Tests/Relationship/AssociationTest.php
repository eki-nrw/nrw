<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Tests\Relationship;

use Eki\NRW\Component\REA\Relationship\Association;
use Eki\NRW\Component\REA\Relationship\RelationshipInterface;
use Eki\NRW\Component\REA\Agent\AgentInterface;
use Eki\NRW\Mdl\REA\Relationship\AssociationInterface;
use Eki\NRW\Mdl\REA\Relationship\Constants as RelationshipConstants;

use PHPUnit\Framework\TestCase;

class AssociationTest extends TestCase
{
    public function testInterfaces()
    {
    	$association = $this->getMockBuilder(Association::class)
			->disableOriginalConstructor()
    		->getMock()
    	;

		$this->assertInstanceOf(RelationshipInterface::class, $association);
		$this->assertInstanceOf(AssociationInterface::class, $association);
    }

    public function testChainTypes()
    {
    	$association = new Association('an_association_type');

		$this->assertSame(RelationshipConstants::REA_RELATIONSHIP_ASSOCIATION, $association->getCategorizationType());
		$this->assertSame('an_association_type', $association->getMainType());
    }

    public function testDefaults()
    {
    	$association = new Association('an_association_type');

		$this->assertNull($association->getAgent());
		$this->assertNull($association->getOtherAgent());
    }
}
