<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Relating\Tests\ContextDiagram;

use Eki\NRW\Component\Relating\ContextDiagram\Context;
use Eki\NRW\Component\Relating\ContextDiagram\ContextInterface;
use Eki\NRW\Component\Relating\Relation\RelationInterface;
use Eki\NRW\Component\Relating\Relation\Relationship;
use Eki\NRW\Component\Relating\Relation\Group;
use Eki\NRW\Component\Relating\Relation\Relation;

use PHPUnit\Framework\TestCase;

use stdClass;

class ContextTest extends TestCase
{
	public function testConstructor()
	{
		$central = new stdClass;
		$context = new Context($central, []);
		
		$this->assertInstanceOf(ContextInterface::class, $context);
		$this->assertEquals($central, $context->getCentral());
	}

	/**
	* @expectedException \InvalidArgumentException
	*/
	public function testConstructor_Wrong()
	{
		$context = new Context(new stdClass, array(new stdClass));
	}

	/**
	* @expectedException \InvalidArgumentException
	*/
	public function testConstructor_Wrong()
	{
		$context = new Context("dahfaslkfhdlkf", []);
	}
	
	public function testGetRelations_Types_Is_Null()
	{
		$central = new stdClass;
		$context = new Context(
			$central, 
			array(
				new Relation(),
				new Group(),
				new Relationship()
			)
		);
		
		$this->assertEquals(4, count($context->getRelations()));
	}
}
