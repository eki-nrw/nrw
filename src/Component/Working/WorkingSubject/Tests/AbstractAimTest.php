<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Tests;

use Eki\NRW\Component\Working\WorkingSubject\AbstractAim;
use Eki\NRW\Component\Working\WorkingSubject\ToolInterface;

use Eki\NRW\Component\Working\WorkingSubject\Tests\Helper\Logger;
use Eki\NRW\Component\Working\WorkingSubject\Tests\Helper\WorkingTool;

use PHPUnit\Framework\TestCase;
use stdClass;

class AbstractAimTest extends TestCase
{
	public function testConstructor()
	{
		$aim = $this->createAim("name", "type", []);
		$aim = $this->createAim("name", ["type"], []);
	}

	/**
	* @expectedException \InvalidArgumentException
	* 
	*/
	public function testConstructorWrong()
	{
		$aim = $this->createAim("name", 100, []);
	}

	public function testSupport()
	{
		$tool = $this->createTool([
			stdClass::class => function ($subject) {
				return $subject->type;
			}
		]);
		$options = [];
		$options['workingTool'] = $tool;
		
		$sType = "subject.type";
		$aim = $this->createAim("name", $sType, $options);
		
		$subject = new stdClass;
		$subject->type = $sType;
		$this->assertTrue($aim->support("name", $subject));
	}

	public function testSupport_SupportType()
	{
		$tool = $this->createTool([
			stdClass::class => function ($subject) {
				return $subject->type;
			}
		]);
		$options = [];
		$options['workingTool'] = $tool;
		
		$sType = str_shuffle("abasbbbckjahfjhahflhaslkhfdlas");
		$aim = $this->createAim("name", $sType, $options, ['_supportType']);
		
		$aim
			->expects($this->exactly(2))
			->method('_supportType')
			->will($this->returnCallback(function ($subject) {
				return true;
			}))
		;
		
		$subject = new stdClass;
		$this->assertTrue($aim->support("name", $subject));
		$this->assertTrue($aim->support("name", null));
	}

	public function testSupport_SupportMore()
	{
		$tool = $this->createTool([
			stdClass::class => function ($subject) {
				return $subject->type;
			}
		]);
		$options = [];
		$options['workingTool'] = $tool;
		
		$sType = "subject.type";
		$aim = $this->createAim("name", $sType, $options, ['_supportMore']);
		
		$aim
			->expects($this->once())
			->method('_supportMore')
			->will($this->returnCallback(function ($name, $subject) {
				return false;
			}))
		;
		
		$subject = new stdClass;
		$subject->type = $sType;
		$this->assertFalse($aim->support("name", $subject));
	}
	
	public function testSetTool()
	{
		$tool = WorkingTool::createTool($this, []);
		$aim = $this->createAim("name", "type", []);
		$aim->setTool($tool);
	}
	
	public function testSetLogger()
	{
		$logger = Logger::createConsoleLogger($this);
		$aim = $this->createAim("name", "type", []);
		$aim->setLogger($logger);
	}
	
	private function createTool(array $getTypes)
	{
		return WorkingTool::createTool($this, $getTypes);
	}
	
	private function createAim($name, $types, array $options, array $methods = [])
	{
		$aim = $this->getMockBuilder(AbstractAim::class)
			->setMethods($methods)
			->setConstructorArgs([$name, $types, $options])			
			->getMockForAbstractClass()
		;
		
		return $aim;
	}
}
