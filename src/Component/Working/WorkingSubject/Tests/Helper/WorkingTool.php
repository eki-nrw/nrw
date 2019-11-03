<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Tests\Helper;

use Eki\NRW\Component\Working\WorkingSubject\ToolInterface;

use PHPUnit\Framework\TestCase;
use stdClass;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
final class WorkingTool
{
	static public function createTool(TestCase $testCase, array $getTypes)
	{
		$tool = $testCase->getMockBuilder(ToolInterface::class)
			->setMethods(['getSubjectType', 'createSubject'])
			->getMockForAbstractClass()
		;
		
		$tool
			->expects($testCase->any())
			->method('getSubjectType')
			->will($testCase->returnCallback(function ($subject) use ($getTypes) {
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

		$tool
			->expects($testCase->any())
			->method('createSubject')
			->will($testCase->returnCallback(function ($subjectType) {
				$subject = new stdClass;
				$subject->type = $subjectType;
				return $subject;
			}))
		;
		
		return $tool;
	}
}
