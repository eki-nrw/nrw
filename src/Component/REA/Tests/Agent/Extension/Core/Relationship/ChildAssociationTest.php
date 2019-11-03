<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Tests\Agent\Extension\Core\Relationship;

use Eki\NRW\Component\REA\Agent\Extension\Core\Relationship\ChildAssociation;

use PHPUnit\Framework\TestCase;

class ChildAssociationTypeTest extends TestCase
{
	public function testType()
	{
    	$association = new ChildAssociation();
    	
    	$this->assertSame('child', $association->getMainType());
	}
}
