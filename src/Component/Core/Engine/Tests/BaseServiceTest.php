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

use Eki\NRW\Component\Base\Engine\Engine as EngineInterface;
//use Eki\NRW\Component\Base\Persistence\Handler as PersistenceHandlerInterface;
use Eki\NRW\Component\Base\Engine\PermissionResolver as PermissionResolverInterface;
use Eki\NRW\Component\Notification\NotificatorInterface;

use Eki\NRW\Component\Core\Engine\BaseService;
use Eki\NRW\Component\Base\Engine\Engine as EngineInterface;

use PHPUnit\Framework\TestCase;

use stdClass;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class BaseServiceTest extends TestCase
{
	public function testConstructor()
	{
		$baseService = $this->getMockBuilder(BaseService::class)
			->setConstructorArgs(array(
				$this->getMockBuilder(EngineInterface::class)->getMockForAbstractClass(),
				array(),
				$this->getMockBuilder(PermissionResolverInterface::class)->getMockForAbstractClass(),
				$this->getMockBuilder(NotificatorInterface::class)->getMockForAbstractClass(),
			))
			->getMockForAbstractClass()
		;
		
		$this->assertNotNull($baseService);
	}
}
