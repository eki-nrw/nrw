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

use Eki\NRW\Mdl\REA\Relationship\DualityInterface;
use Eki\NRW\Mdl\REA\Relationship\Duality;
use Eki\NRW\Mdl\REA\Relationship\RelationshipInterface;
use Eki\NRW\Mdl\REA\Event\EventInterface;
use Eki\NRW\Mdl\REA\Relationship\Constants as RelationshipConstants;

use PHPUnit\Framework\TestCase;

class DualityTest extends TestCase
{
    public function testInterfaces()
    {
    	$duality = $this->getMockBuilder(Duality::class)
			->disableOriginalConstructor()
    		->getMock()
    	;

		$this->assertInstanceOf(RelationshipInterface::class, $duality);
		$this->assertInstanceOf(DualityInterface::class, $duality);
    }

    public function testChainTypes()
    {
    	$duality = new Duality('an_duality_type');

		$this->assertSame(RelationshipConstants::REA_RELATIONSHIP_DUALITY, $duality->getCategorizationType());
		$this->assertSame('an_duality_type', $duality->getMainType());
    }

    public function testDefaults()
    {
    	$duality = new Duality('an_duality_type');

		$this->assertNull($duality->getEvent());
		$this->assertNull($duality->getOtherEvent());
    }
}
