<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Tests\Agent;

use Eki\NRW\Component\REA\Agent\AbstractAgent;

use PHPUnit\Framework\TestCase;

class AbstractAgentTest extends TestCase
{
	private $agent;
	
	public function setUp()
	{
    	$this->agent = $this->getMockBuilder(AbstractAgent::class)->getMockForAbstractClass();
	}
	
	public function tearDown()
	{
		$this->agent = null;
	}
	
	public function testDefault()
	{
		
	}
}
