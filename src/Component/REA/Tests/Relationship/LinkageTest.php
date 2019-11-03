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

use Eki\NRW\Mdl\REA\Relationship\LinkageInterface;
use Eki\NRW\Mdl\REA\Relationship\Linkage;
use Eki\NRW\Mdl\REA\Relationship\RelationshipInterface;
use Eki\NRW\Mdl\REA\Resource\ResourceInterface;
use Eki\NRW\Mdl\REA\Relationship\Constants as RelationshipConstants;

use PHPUnit\Framework\TestCase;

class LinkageTest extends TestCase
{
    public function testInterfaces()
    {
    	$linkage = $this->getMockBuilder(Linkage::class)
			->disableOriginalConstructor()
    		->getMock()
    	;

		$this->assertInstanceOf(RelationshipInterface::class, $linkage);
		$this->assertInstanceOf(LinkageInterface::class, $linkage);
    }

    public function testChainTypes()
    {
    	$linkage = new Linkage('an_linkage_type');

		$this->assertSame(RelationshipConstants::REA_RELATIONSHIP_LINKAGE, $linkage->getCategorizationType());
		$this->assertSame('an_linkage_type', $linkage->getMainType());
    }

    public function testDefaults()
    {
    	$linkage = new Linkage('an_linkage_type');

		$this->assertNull($linkage->getResource());
		$this->assertNull($linkage->getOtherResource());
    }
}
