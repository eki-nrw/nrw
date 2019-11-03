<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Agent;

use Eki\NRW\Mdl\REA\Agent\AssociationInterface;
use Eki\NRW\Mdl\REA\Agent\AbstractAssociation;

use Eki\NRW\Mdl\REA\Relationship\Association;

use PHPUnit\Framework\TestCase;

class AbstractAssociationTest extends TestCase
{
	private $association;
	
	public function setUp()
	{
    	$this->association = $this->getMockBuilder(AbstractAssociation::class)->getMockForAbstractClass();
	}
	
	public function tearDown()
	{
		$this->association = null;
	}

	public function testInterfaces()
	{
    	$association = $this->association;

		$this->assertInstanceOf(AssociationInterface::class, $association);
		$this->assertInstanceOf(Association::class, $association);
	}
}
