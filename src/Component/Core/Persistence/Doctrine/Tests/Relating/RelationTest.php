<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Relating;

use Eki\NRW\Component\SPBase\Persistence\Relating\Relation as RelationInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\Relating\Relation;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Relating\Fixtures\AObject;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Relating\Fixtures\BObject;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Relating\Fixtures\CObject;

use PHPUnit\Framework\TestCase;

use stdClass;

class RelationTest extends TestCase
{
	public function testInterfaces()
	{
		$relation = new Relation();
		
		$this->assertInstanceOf(RelationInterface::class, $relation);
	}
	
	public function testSetBaseIsObjectWithGetId()
	{
		$relation = new Relation();
		
		$obj = new AObject();
		$relation->setBase($obj);
	}

	public function testSetBaseIsArrayWithIdAndClass()
	{
		$relation = new Relation();
		
		$obj = new AObject();
		$obj->setId(99);

		$relation->setBase(array(
			'id' => $obj->getId(0),
			'class' => get_class($obj)
		));
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testSetBaseIsObjectWithoutGetId()
	{
		$relation = new Relation();
		
		$obj = new stdClass();
		$relation->setBase($obj);
	}

	/**
	* @dataProvider getInvalidSetBaseInputs
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testSetBaseIsNotArrayOrNotObject($input)
	{
		$relation = new Relation();
		
		$relation->setBase($input);
	}
	
	public function getInvalidSetBaseInputs()
	{
		return [
			[ 100 ],
			[ "abcdfejklasf" ],
			[ 9.82 ],
			[ true ],
			[ false ],
		];
	}
	
	public function testGetBaseAlwaysReturnArrayWithIdAndClassIndex()
	{
		$relation = new Relation();	

		$obj = new AObject();
		$obj->setId(9999);
		$relation->setBase($obj);
		
		$base = $relation->getBase();
		$this->assertTrue(is_array($base));
		$this->assertTrue(isset($base['id']));
		$this->assertTrue(isset($base['class']));
		$this->assertSame(get_class($obj), $base['class']);
	}
	
	public function testGetOthersNotYetSet()
	{
		$relation = new Relation();	

		$this->assertTrue(is_array($relation->getOthers()));
		$this->assertEmpty($relation->getOthers());
	}

	public function testSetOthersOfAllObjects()
	{
		$relation = new Relation();	

		$a = new AObject();
		$b = new BObject();
		$c = new CObject();
		
		$relation->setOthers(array(
			'a' => $a,
			'b' => $b,
			'c' => $c
		));

		$this->assertTrue(is_array($relation->getOthers()));
		$this->assertNotEmpty($relation->getOthers());
	}

	/**
	* @dataProvider getMixedOthersInputs
	* 
	*/
	public function testSetOthersOfMixArraysAndObjects(array $others)
	{
		$relation = new Relation();	
		$relation->setOthers($others);

		$this->assertTrue(is_array($relation->getOthers()));
		$this->assertNotEmpty($relation->getOthers());
	}
	
	public function getMixedOthersInputs()
	{
		return [
			[
				array(
					'a' => new AObject(),
					'b' => new BObject(),
					'c' => new CObject(),
				)
			],
			[
				array(
					'a' => new AObject(),
					'arr' => array( 'id' => 99, 'class' => stdClass::class ),
				)
			],
			[
				array(
					'arr_1' => array( 'id' => 111, 'class' => stdClass::class ),
					'arr_2' => array( 'id' => 222, 'class' => stdClass::class ),
				)
			],
		];
	}

	/**
	* @dataProvider getMixedOthersWrongInputs
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testSetOthersOfMixArraysAndObjects_Wrong(array $others)
	{
		$relation = new Relation();	
		$relation->setOthers($others);
	}

	public function getMixedOthersWrongInputs()
	{
		return [
			[
				array(
					'a' => new stdClass(),
					'b' => new BObject(),
					'c' => new CObject(),
				)
			],
			[
				array(
					'a' => new AObject(),
					'arr' => array( 'id' => 99, 'class' => stdClass::class ),
					'wrong_arrary' => array( 'wrong_index' => 100 ),
				)
			],
			[
				array(
					'arr_1' => array( 'id' => 111, ),
					'arr_2' => array( 'class' => stdClass::class ),
				)
			],
		];
	}
	
	public function testGetOther()
	{
		$relation = new Relation();	

		$a = new AObject();
		$a->setId(1);
		$b = new BObject();
		$b->setId(2);
		$c = new CObject();
		$c->setId(3);
		
		$relation->setOthers(array(
			'a' => $a,
			'b' => $b,
			'c' => $c
		));
		
		foreach(array('a' => $a, 'b' => $b, 'c' => $c) as $key => $obj)
		{
			$o = $relation->getOther($key);
			$this->assertTrue(is_array($o));
			$this->assertTrue(isset($o['id']));
			$this->assertTrue(isset($o['class']));
			$this->assertSame(get_class($obj), $o['class']);
		}
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testGetOtherWithWrongKey()
	{
		$relation = new Relation();	

		$a = new AObject();
		$a->setId(1);
		$b = new BObject();
		$b->setId(2);
		
		$relation->setOthers(array(
			'a' => $a,
			'b' => $b,
		));

		$relation->getOther('wrong_key');		
	}
	
	public function testAddOtherIsObjectWithGetId()
	{
		$relation = new Relation();
		
		$a = new AObject();
		$a->setId(1);
		$b = new BObject();
		$b->setId(2);
		$c = new CObject();
		$c->setId(3);
		
		$relation->addOther($a, 'a');
		$relation->addOther($b, 'b');
		$relation->addOther($c, 'c');
		
		$this->assertSame(3, count($relation->getOthers()));
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testAddOtherWithTheSameKeyIsNotAccepted()
	{
		$relation = new Relation();
		
		$a = new AObject();
		$a->setId(1);
		$b = new BObject();
		$b->setId(2);
		
		$relation->addOther($a, 'a');
		$relation->addOther($b, 'a');
	}

	public function testHasOther()
	{
		$relation = new Relation();
		
		$a = new AObject();
		$a->setId(1);
		$b = new BObject();
		$b->setId(2);
		$c = new CObject();
		$c->setId(3);
		
		$relation->addOther($a, 'a');
		$relation->addOther($b, 'b');
		$relation->addOther($c, 'c');
		
		$this->assertTrue($relation->hasOther('a'));
		$this->assertTrue($relation->hasOther('b'));
		$this->assertTrue($relation->hasOther('c'));
	}

	public function testRemoveOther()
	{
		$relation = new Relation();
		
		$a = new AObject();
		$a->setId(1);
		$b = new BObject();
		$b->setId(2);
		$c = new CObject();
		$c->setId(3);
		
		$relation->addOther($a, 'a');
		$relation->addOther($b, 'b');
		$relation->addOther($c, 'c');
		$this->assertSame(3, count($relation->getOthers()));
		
		$relation->removeOther('a');
		$this->assertSame(2, count($relation->getOthers()));
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
	public function testRemoveOtherNotInOthers()
	{
		$relation = new Relation();
		
		$a = new AObject();
		$a->setId(1);
		$b = new BObject();
		$b->setId(2);
		$c = new CObject();
		$c->setId(3);
		
		$wrong = new stdClass();
		
		$relation->addOther($a, 'a');
		$relation->addOther($b, 'b');
		$relation->addOther($c, 'c');
		$this->assertSame(3, count($relation->getOthers()));
		
		$relation->removeOther('wrong_key');
	}
}
