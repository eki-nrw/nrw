<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Tests\Helper;

use Eki\NRW\Component\Base\Engine\PermissionResolver as PermissionResolverInterface;

use PHPUnit\Framework\TestCase;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
final class PermissionResolverHelper
{
	static public function createNullPermissionResolver(TestCase $testCase, $easyUser)
	{
		$resolver = $testCase->getMockBuilder(PermissionResolverInterface::class)
			->setMethods(['hasAccess', 'canUser'])
			->getMockForAbstractClass()
		;
		
		$resolver
			->expects($testCase->any())
			->method('hasAccess')
			->will($testCase->returnValue($easyUser))
		;

		$resolver
			->expects($testCase->any())
			->method('canUser')
			->will($testCase->returnValue($easyUser))
		;
		
		return $resolver;
	}
}
