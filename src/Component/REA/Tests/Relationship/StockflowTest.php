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

use Eki\NRW\Mdl\REA\Relationship\StockflowInterface;
use Eki\NRW\Mdl\REA\Relationship\Stockflow;
use Eki\NRW\Mdl\REA\Relationship\RelationshipInterface;
use Eki\NRW\Mdl\REA\Resource\ResourceInterface;
use Eki\NRW\Mdl\REA\Event\EventInterface;
use Eki\NRW\Mdl\REA\Relationship\Constants as RelationshipConstants;

use PHPUnit\Framework\TestCase;

class StockflowTest extends TestCase
{
    public function testInterfaces()
    {
    	$stockflow = $this->getMockBuilder(Stockflow::class)
			->disableOriginalConstructor()
    		->getMock()
    	;

		$this->assertInstanceOf(RelationshipInterface::class, $stockflow);
		$this->assertInstanceOf(StockflowInterface::class, $stockflow);
    }

    public function testChainTypes()
    {
    	$stockflow = new Stockflow('an_stockflow_type');

		$this->assertSame(RelationshipConstants::REA_RELATIONSHIP_STOCKFLOW, $stockflow->getCategorizationType());
		$this->assertSame('an_stockflow_type', $stockflow->getMainType());
    }

    public function testDefaults()
    {
    	$stockflow = new Stockflow('an_stockflow_type');

		$this->assertNull($stockflow->getEvent());
		$this->assertNull($stockflow->getResource());
    }
}
