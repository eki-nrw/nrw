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

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;

use PHPUnit\Framework\TestCase;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
final class Logger
{
	static public function createConsoleLogger(TestCase $testCase)
	{
		$logger = $testCase->getMockBuilder(AbstractLogger::class)
			->setMethods(['log'])
			->getMockForAbstractClass()
		;
		
		$logger
			->expects($testCase->any())
			->method('log')
			->will($testCase->returnCallback(function ($level, $message, array $context = array()) {
				echo "[".$level."]: ".$message."\n";
			}))
		;

		return $logger;
	}
}
