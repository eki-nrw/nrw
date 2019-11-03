<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Processing\Process\Transform;

use Eki\NRW\Mdl\REA\Processing\Process\Transform\TransformsAwareTrait;
use Eki\NRW\Mdl\REA\Processing\Process\Transform\TransformInterface;

use PHPUnit\Framework\TestCase;

use stdClass;

class TransformsAwareTraitTest extends TestCase
{
	public function testInternal()
	{
		$transform_a = $this->createTransform();
		$transform_b = $this->createTransform();
		
		$this->assertNotSame($transform_a, $transform_b);
	}
	
	public function testAddTransform()
	{
		$transforms = $this->createTransformsAwareTrait();

		$transform_a = $this->createTransform();
		$transforms->addTransform($transform_a, 'key_a');

		$transform_b = $this->createTransform();
		$transforms->addTransform($transform_b, 'key_b');
		
		$this->assertTrue($transforms->hasTransform('key_a'));
		$this->assertTrue($transforms->hasTransform('key_b'));
		
		$this->assertSame($transform_a, $transforms->getTransform('key_a'));
		$this->assertSame($transform_b, $transforms->getTransform('key_b'));
	}

    /**
     * @expectedException \InvalidArgumentException
     */
	public function testAddTransform_Twice()
	{
		$transforms = $this->createTransformsAwareTrait();

		$transform = $this->createTransform();
		$transforms->addTransform($transform, 'key_a');
		$transforms->addTransform($transform, 'key_b');
	}

    /**
     * @expectedException InvalidArgumentException
     */
	public function testAddTransform_SameKey()
	{
		$transforms = $this->createTransformsAwareTrait();

		$transform_1 = $this->createTransform();
		$transforms->addTransform($transform_1, 'key_same');

		$transform_2 = $this->createTransform();
		$transforms->addTransform($transform_1, 'key_same');
	}

	public function testRemoveTransform()
	{
		$transforms = $this->createTransformsAwareTrait();

		$transform_a = $this->createTransform();
		$transforms->addTransform($transform_a, 'key_a');

		$transform_b = $this->createTransform();
		$transforms->addTransform($transform_b, 'key_b');

		$transform_c = $this->createTransform();
		$transforms->addTransform($transform_c, 'key_c');
		
		$this->assertEquals(3, sizeof($transforms->getTransforms()));
		
		$transforms->removeTransform($transform_a);

		$this->assertEquals(2, sizeof($transforms->getTransforms()));
		
		$transforms->removeTransformByKey('key_c');

		$this->assertEquals(1, sizeof($transforms->getTransforms()));
	}

    /**
     * @expectedException \LogicException
     */
	public function testRemoveTransform_NoTransform()
	{
		$transforms = $this->createTransformsAwareTrait();

		$transform_a = $this->createTransform();
		$transforms->addTransform($transform_a, 'key_a');

		$transform_b = $this->createTransform();
		$transforms->addTransform($transform_b, 'key_b');

		$transform_c = $this->createTransform();
		// No adding
		
		$transforms->removeTransform($transform_c);
	}

    /**
     * @expectedException InvalidArgumentException
     */
	public function testRemoveTransform_NoKey()
	{
		$transforms = $this->createTransformsAwareTrait();

		$transform_a = $this->createTransform();
		$transforms->addTransform($transform_a, 'key_a');

		$transform_b = $this->createTransform();
		$transforms->addTransform($transform_b, 'key_b');

		$transforms->removeTransformByKey('key_c');
	}

	public function testTransforms()
	{
		$transforms = $this->createTransformsAwareTrait();

		$this->assertEmpty($transforms->getTransforms());
		
		$transforms->setTransforms(array(
			'key_x' => $this->createTransform(),
			'key_y' => $this->createTransform(),
		));
		
		$this->assertEquals(2, sizeof($transforms->getTransforms()));
	}

    /**
     * @expectedException InvalidArgumentException
     */
	public function testTransforms_SetNotTransform()
	{
		$transforms = $this->createTransformsAwareTrait();

		$this->assertEmpty($transforms->getTransforms());
		
		$transforms->setTransforms(array(
			'key_good' => $this->createTransform(),
			'key_wrong' => new stdClass,
		));
	}


	private function createTransformsAwareTrait()
	{
		return $this->getMockBuilder(TransformsAwareTrait::class)
			->getMockForTrait()
		;
	}
	
	private function createTransform()
	{
		return $this->getMockBuilder(TransformInterface::class)->getMock();
	}
}
