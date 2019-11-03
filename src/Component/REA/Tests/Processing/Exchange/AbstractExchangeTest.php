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

use Eki\NRW\Mdl\REA\Processing\Exchange\AbstractExchange;
use Eki\NRW\Mdl\REA\Processing\Exchange\ExchangeInterface;

use PHPUnit\Framework\TestCase;

class AbstractExchangeTest extends EventBasedTest
{
	public function setUp()
	{
		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}
	
    public function testInterfaces()
    {
    	$exchange = $this->createExchange();
    	
    	$this->assertInstanceOf(ExchangeInterface::class, $exchange);
	}
	
	public function getFirstNewm()
	{
    	$exchange = $this->createExchange();
    	
    	$this->assertEmpty($exchange->getExchangeName());
    	$this->assertEmpty($exchange->getProvideEvent());
    	$this->assertNull($exchange->getReceiveEvent());
		
	}

    public function testExchangeName()
    {
    	$exchange = $this->createExchange();

		$exchange->setExchangeName('exchange name');
		$this->assertSame('exchange name', $exchange->getExchangeName());    	
	}

	public function testEventPair()
	{
    	$exchange = $this->createExchange();
    	
    	$provideEvent_a = $this->createProvideEvent();
    	$receiveEvent_a = $this->createReceiveEvent();
    	$exchange->addEventPair($provideEvent_a, $receiveEvent_a, 'key_a');
    	
    	$eventPair_a = $exchange->getEventPair('key_a');
    	$this->assertEquals($eventPair_a['provide'], $provideEvent_a);
    	$this->assertEquals($eventPair_a['receive'], $receiveEvent_a);

    	$provideEvent_b = $this->createProvideEvent();
    	$receiveEvent_b = $this->createReceiveEvent();
    	$exchange->addEventPair($provideEvent_b, $receiveEvent_b, 'key_b');
		
    	$eventPair_b = $exchange->getEventPair('key_b');
    	$this->assertEquals($eventPair_b['provide'], $provideEvent_b);
    	$this->assertEquals($eventPair_b['receive'], $receiveEvent_b);
    	
    	$this->assertEquals(
    		array(
    			'key_a' => array( 'provide' => $provideEvent_a, 'receive' => $receiveEvent_a ),
    			'key_b' => array( 'provide' => $provideEvent_b, 'receive' => $receiveEvent_b ),
    		),
    		$exchange->getEventPairs()
    	);
	}

	
	private function createExchange()
	{
    	return $this->getMockBuilder(AbstractExchange::class)
    		->getMockForAbstractClass()
    	;
	}
}
