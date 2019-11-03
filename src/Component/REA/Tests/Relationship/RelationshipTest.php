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

use Eki\NRW\Mdl\REA\Relationship\RelationshipInterface;
use Eki\NRW\Mdl\REA\Relationship\Constants as RelationshipConstants;
use Eki\NRW\Mdl\REA\Relationship\Relationship;
use Eki\NRW\Common\Relations\RelationInterface;
use Eki\NRW\Common\Relations\TypeMeaningInterface;

use PHPUnit\Framework\TestCase;

class RelationshipTest extends TestCase
{
	public function testInterfaces()
	{
		$relationship = $this->getMockBuilder(Relationship::class)
			->disableOriginalConstructor()
			->getMock()
		;
		
		$this->assertInstanceOf(RelationshipInterface::class, $relationship);
		$this->assertInstanceOf(RelationInterface::class, $relationship);
		$this->assertInstanceOf(TypeMeaningInterface::class, $relationship);
	}
	
    public function testTypeChain()
    {
    	$relationship = new Relationship('rea_type', 'main_type', 'sub_type');

		$this->assertSame(RelationshipConstants::REA_DOMAIN, $relationship->getRelationDomain());
		$this->assertSame('rea_type', $relationship->getCategorizationType());
		$this->assertSame('main_type', $relationship->getMainType());
		$this->assertSame('sub_type', $relationship->getSubType());
		
		$this->assertEmpty($relationship->getLabel());
		$this->assertNull($relationship->getValue());
    }

    public function testDefaults()
    {
    	$relationship = new Relationship('rea_type', 'main_type');

		$this->assertSame(RelationshipConstants::REA_DOMAIN, $relationship->getRelationDomain());
		$this->assertSame('rea_type', $relationship->getCategorizationType());
		$this->assertSame('main_type', $relationship->getMainType());
		$this->assertEmpty($relationship->getSubType());
		
		$this->assertEmpty($relationship->getLabel());
		$this->assertNull($relationship->getValue());
    }
}
