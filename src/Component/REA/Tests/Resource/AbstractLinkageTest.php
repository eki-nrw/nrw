<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Resource;

use Eki\NRW\Mdl\REA\Resource\LinkageInterface;
use Eki\NRW\Mdl\REA\Resource\AbstractLinkage;

use Eki\NRW\Mdl\REA\Relationship\Linkage;

use PHPUnit\Framework\TestCase;

class AbstractLinkageTest extends TestCase
{
	private $linkage;
	
	public function setUp()
	{
    	$this->linkage = $this->getMockBuilder(AbstractLinkage::class)->getMockForAbstractClass();
	}
	
	public function tearDown()
	{
		$this->linkage = null;
	}

	public function testInterfaces()
	{
    	$linkage = $this->linkage;

		$this->assertInstanceOf(LinkageInterface::class, $linkage);
		$this->assertInstanceOf(Linkage::class, $linkage);
	}
}
