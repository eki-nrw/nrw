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

use Eki\NRW\Mdl\REA\Relationship\CustodyInterface;
use Eki\NRW\Mdl\REA\Relationship\Custody;
use Eki\NRW\Mdl\REA\Relationship\RelationshipInterface;
use Eki\NRW\Mdl\REA\Resource\ResourceInterface;
use Eki\NRW\Mdl\REA\Agent\AgentInterface;
use Eki\NRW\Mdl\REA\Relationship\Constants as RelationshipConstants;

use PHPUnit\Framework\TestCase;

class CustodyTest extends TestCase
{
    public function testInterfaces()
    {
    	$custody = $this->getMockBuilder(Custody::class)
			->disableOriginalConstructor()
    		->getMock()
    	;

		$this->assertInstanceOf(RelationshipInterface::class, $custody);
		$this->assertInstanceOf(CustodyInterface::class, $custody);
    }

    public function testChainTypes()
    {
    	$custody = new Custody('an_custody_type');

		$this->assertSame(RelationshipConstants::REA_RELATIONSHIP_CUSTODY, $custody->getCategorizationType());
		$this->assertSame('an_custody_type', $custody->getMainType());
    }

    public function testDefaults()
    {
    	$custody = new Custody('an_custody_type');

		$this->assertNull($custody->getAgent());
		$this->assertNull($custody->getResource());
    }
}
