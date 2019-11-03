<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Event\Extension\Core\Relationship;

use Eki\NRW\Mdl\REA\Event\Extension\Core\Relationship\TransferDuality;

use PHPUnit\Framework\TestCase;

class TransferDualityTypeTest extends TestCase
{
	public function testType()
	{
    	$duality = new TransferDuality();
    	
    	$this->assertSame('transfer', $duality->getMainType());
	}
}
