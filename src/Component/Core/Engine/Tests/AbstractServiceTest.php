<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Tests;

use Eki\NRW\Component\Core\Engine\AbstractService;
use Eki\NRW\Component\Base\Engine\Engine as EngineInterface;

use PHPUnit\Framework\TestCase;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class AbstractServiceTest extends TestCase
{
	public function testConstructor()
	{
		$abstractService = $this->getMockBuilder(AbstractService::class)
			->setConstructorArgs(array(
				$this->getMockBuilder(EngineInterface::class)->getMockForAbstractClass(),
				array()
			))
			->getMockForAbstractClass()
		;
		
		$this->assertNotNull($abstractService);
	}
}

