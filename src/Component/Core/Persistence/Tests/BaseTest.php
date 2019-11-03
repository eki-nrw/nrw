<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
abstract class BaseTest extends TestCase
{
	/**
	* @dataProvider getArgumentsToCreateHandler
	* 
	*/
	public function testCreateHandler($handlerClass, array $arguments)
	{
		return $this->createHandler($handlerClass, $arguments);
	}
	
	public function getArgumentsToCreateHandler()
	{
		return $this->__getArgumentsToCreateHandler();
	}
	
	abstract protected function __getArgumentsToCreateHandler();
	
	abstract protected function createHandler($handlerClass, array $arguments);
}

