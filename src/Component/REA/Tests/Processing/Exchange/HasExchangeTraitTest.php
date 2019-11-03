<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Processing\Exchange;

use Eki\NRW\Mdl\REA\Processing\Exchange\HasExchangeTrait;
use Eki\NRW\Mdl\REA\Processing\Exchange\ExchangeInterface;

use PHPUnit\Framework\TestCase;

class HasExchangeTraitTest extends TestCase
{
	public function testHasExchange()
	{
		$trait = $this->getMockBuilder(HasExchangeTrait::class)->getMockForTrait();
		$exchange = $this->getMockBuilder(ExchangeInterface::class)->getMock();
		
		$trait->setExchange($exchange);
		$this->assertEquals($exchange, $trait->getExchange());
	}
}
