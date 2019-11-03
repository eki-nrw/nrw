<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Tests\Utils;

use Eki\NRW\Common\Location\LocationInterface;

use PHPUnit\Framework\TestCase;

final class MiscUtils
{
	public static function ceateILocation(TestCase $testCase, $locationName)
	{
		$location = $testCase->getMockBuilder(LocationInterface::class)
			->setMethods('getLocationName')
			->getMockForAbstractClass()
		;
		
		$location->expects($testCase->any())
			->method('getLocationName')
			->will($testCase->returnValue($locationName))
		;
		
		return $location;
	}
}
