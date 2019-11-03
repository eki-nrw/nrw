<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Tests\Def\Abc\Xyz;

use Eki\NRW\Component\Working\WorkingSubject\Def\Abc\Xyz\VerbPlace;
use Eki\NRW\Component\Working\WorkingSubject\ToolInterface;

use PHPUnit\Framework\TestCase;
use stdClass;

class VerbPlaceTest extends TestCase
{
	public function testConstructor()
	{
		$aim = $this->createAim([]);
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
		
		$aim = $this->createAim($options);
		
		$subject = new stdClass;
		$subject->type = "abc.xyz";
		$this->assertTrue($aim->support("VerbPlace", $subject));
	}

	private function createTool(array $getTypes)
	{
		$tool = $this->getMockBuilder(ToolInterface::class)
			->setMethods(['getSubjectType'])
			->getMockForAbstractClass()
		;
		
		$tool
			->expects($this->any())
			->method('getSubjectType')
			->will($this->returnCallback(function ($subject) use ($getTypes) {
				$class = get_class($subject);
				if (isset($getTypes[$class]))
				{
					$f = $getTypes[$class];
					return $f($subject);
				}
				else
					return null;
			}))
		;
		
		return $tool;
	}

	private function createAim(array $options)
	{
		$aim = new VerbPlace($options);
		
		return $aim;
	}
}
