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

use Eki\NRW\Mdl\REA\Processing\Exchange\Transfer\TransfersAwareTrait;
use Eki\NRW\Mdl\REA\Processing\Exchange\Transfer\TransferInterface;

use PHPUnit\Framework\TestCase;

use stdClass;

class TransferAwareTraitTest extends TestCase
{
	public function testInternal()
	{
		$transform_a = $this->createTransfer();
		$transform_b = $this->createTransfer();
		
		$this->assertNotSame($transform_a, $transform_b);
	}
	
	public function testAddTransfer()
	{
		$transforms = $this->createTransfersAwareTrait();

		$transform_a = $this->createTransfer();
		$transforms->addTransfer($transform_a, 'key_a');

		$transform_b = $this->createTransfer();
		$transforms->addTransfer($transform_b, 'key_b');
		
		$this->assertTrue($transforms->hasTransfer('key_a'));
		$this->assertTrue($transforms->hasTransfer('key_b'));
		
		$this->assertSame($transform_a, $transforms->getTransfer('key_a'));
		$this->assertSame($transform_b, $transforms->getTransfer('key_b'));
	}

    /**
     * @expectedException \InvalidArgumentException
     */
	public function testAddTransfer_Twice()
	{
		$transforms = $this->createTransfersAwareTrait();

		$transform = $this->createTransfer();
		$transforms->addTransfer($transform, 'key_a');
		$transforms->addTransfer($transform, 'key_b');
	}

    /**
     * @expectedException InvalidArgumentException
     */
	public function testAddTransfer_SameKey()
	{
		$transforms = $this->createTransfersAwareTrait();

		$transform_1 = $this->createTransfer();
		$transforms->addTransfer($transform_1, 'key_same');

		$transform_2 = $this->createTransfer();
		$transforms->addTransfer($transform_1, 'key_same');
	}

	public function testRemoveTransfer()
	{
		$transforms = $this->createTransfersAwareTrait();

		$transform_a = $this->createTransfer();
		$transforms->addTransfer($transform_a, 'key_a');

		$transform_b = $this->createTransfer();
		$transforms->addTransfer($transform_b, 'key_b');

		$transform_c = $this->createTransfer();
		$transforms->addTransfer($transform_c, 'key_c');
		
		$this->assertEquals(3, sizeof($transforms->getTransfers()));
		
		$transforms->removeTransfer($transform_a);

		$this->assertEquals(2, sizeof($transforms->getTransfers()));
		
		$transforms->removeTransferByKey('key_c');

		$this->assertEquals(1, sizeof($transforms->getTransfers()));
	}

    /**
     * @expectedException \LogicException
     */
	public function testRemoveTransfer_NoTransfer()
	{
		$transforms = $this->createTransfersAwareTrait();

		$transform_a = $this->createTransfer();
		$transforms->addTransfer($transform_a, 'key_a');

		$transform_b = $this->createTransfer();
		$transforms->addTransfer($transform_b, 'key_b');

		$transform_c = $this->createTransfer();
		// No adding
		
		$transforms->removeTransfer($transform_c);
	}

    /**
     * @expectedException InvalidArgumentException
     */
	public function testRemoveTransfer_NoKey()
	{
		$transforms = $this->createTransfersAwareTrait();

		$transform_a = $this->createTransfer();
		$transforms->addTransfer($transform_a, 'key_a');

		$transform_b = $this->createTransfer();
		$transforms->addTransfer($transform_b, 'key_b');

		$transforms->removeTransferByKey('key_c');
	}

	public function testTransfers()
	{
		$transforms = $this->createTransfersAwareTrait();

		$this->assertEmpty($transforms->getTransfers());
		
		$transforms->setTransfers(array(
			'key_x' => $this->createTransfer(),
			'key_y' => $this->createTransfer(),
		));
		
		$this->assertEquals(2, sizeof($transforms->getTransfers()));
	}

    /**
     * @expectedException InvalidArgumentException
     */
	public function testTransfers_SetNotTransfer()
	{
		$transforms = $this->createTransfersAwareTrait();

		$this->assertEmpty($transforms->getTransfers());
		
		$transforms->setTransfers(array(
			'key_good' => $this->createTransfer(),
			'key_wrong' => new stdClass,
		));
	}


	private function createTransfersAwareTrait()
	{
		return $this->getMockBuilder(TransfersAwareTrait::class)
			->getMockForTrait()
		;
	}
	
	private function createTransfer()
	{
		return $this->getMockBuilder(TransferInterface::class)->getMock();
	}
}
