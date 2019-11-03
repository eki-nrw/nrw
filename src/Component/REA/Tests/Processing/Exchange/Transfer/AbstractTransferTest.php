<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Processing\Exchange\Transfer;

use Eki\NRW\Mdl\REA\Tests\Processing\Exchange\EventBasedTest;

use Eki\NRW\Mdl\REA\Processing\Exchange\Transfer\AbstractTransfer;

use PHPUnit\Framework\TestCase;

class AbstractTransferTest extends EventBasedTest
{
	public function setUp()
	{
		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}
	
	public function testFirstNew()
	{
		$transfer = $this->createAbstractTransfer();

		$this->assertNull($transfer->getTransferName());
		$this->assertEmpty($transfer->getProvideEvent());
		$this->assertNull($transfer->getReceiveEvent());
	}

	public function testTransferName()
	{
		$transfer = $this->createAbstractTransfer();

		$transfer->setTransferName('transfer name');
		$this->assertSame('transfer name', $transfer->getTransferName());
	}

	public function testProvideEvent()
	{
		$transfer = $this->createAbstractTransfer();
		
		$event = $this->createProvideEvent();
		$transfer->setProvideEvent($event);
		
		$this->assertEquals($event, $transfer->getProvideEvent());
	}

    /**
     * @expectedException \InvalidArgumentException
     */
	public function testProvideEvent_SetReceiveEvent()
	{
		$transfer = $this->createAbstractTransfer();
		
		$event = $this->createReceiveEvent();
		$transfer->setProvideEvent($event);
	}

	public function testReceiveEvent()
	{
		$transfer = $this->createAbstractTransfer();
		
		$event = $this->createReceiveEvent();
		$transfer->setReceiveEvent($event);
		
		$this->assertEquals($event, $transfer->getReceiveEvent());
	}

    /**
     * @expectedException \InvalidArgumentException
     */
	public function testReceiveEvent_SetProvideEvent()
	{
		$transfer = $this->createAbstractTransfer();
		
		$event = $this->createProvideEvent();
		$transfer->setReceiveEvent($event);
	}

	public function testReciprocal()
	{
		$transfer = $this->createAbstractTransfer();
		
		$transfer->setReciprocal(true);
		$this->assertTrue($transfer->isReciprocal());

		$transfer->setReciprocal(false);
		$this->assertFalse($transfer->isReciprocal());
	}

	private function createAbstractTransfer()
	{
		return $this->getMockBuilder(AbstractTransfer::class)
			->getMockForAbstractClass()
		;
	}
}
