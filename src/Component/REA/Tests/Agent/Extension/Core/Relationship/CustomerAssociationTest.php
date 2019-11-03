<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Agent\Extension\Core\Relationship;

use Eki\NRW\Mdl\REA\Agent\Extension\Core\Relationship\CustomerAssociation;

use PHPUnit\Framework\TestCase;

class CustomerRelationshipTypeTest extends TestCase
{
	public function testType()
	{
    	$association = new CustomerAssociation();
    	
    	$this->assertSame('customer', $association->getMainType());
	}
}
