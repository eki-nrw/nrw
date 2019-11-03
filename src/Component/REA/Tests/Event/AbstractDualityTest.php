<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Event;

use Eki\NRW\Mdl\REA\Event\DualityInterface;
use Eki\NRW\Mdl\REA\Event\AbstractDuality;

use Eki\NRW\Mdl\REA\Relationship\Duality;

use PHPUnit\Framework\TestCase;

class AbstractDualityTest extends TestCase
{
	private $duality;
	
	public function setUp()
	{
    	$this->duality = $this->getMockBuilder(AbstractDuality::class)->getMockForAbstractClass();
	}
	
	public function tearDown()
	{
		$this->duality = null;
	}

	public function testInterfaces()
	{
    	$duality = $this->duality;

		$this->assertInstanceOf(DualityInterface::class, $duality);
		$this->assertInstanceOf(Duality::class, $duality);
	}
}
