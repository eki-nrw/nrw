<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Resource\Extension\Core\Relationship;

use Eki\NRW\Mdl\REA\Resource\Extension\Core\Relationship\UnderlyingLinkage;

use PHPUnit\Framework\TestCase;

class UnderlyingLinkageTest extends TestCase
{
	public function testType()
	{
    	$linkage = new UnderlyingLinkage();
    	
    	$this->assertSame('underlying', $linkage->getMainType());
	}
}
