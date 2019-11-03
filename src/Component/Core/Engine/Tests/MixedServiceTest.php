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

use Eki\NRW\Component\Core\Engine\MixedService;

use Eki\NRW\Component\Base\Engine\Engine as EngineInterface;
use Eki\NRW\Component\Base\Engine\PermissionResolver as PermissionResolverInterface;
use Eki\NRW\Component\Notification\NotificatorInterface;
use Eki\NRW\Component\Base\Persistence\Handler as PersistenceHandlerInterface;

use PHPUnit\Framework\TestCase;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class MixedServiceTest extends TestCase
{
	public function testConstructor()
	{
		$mixedService = $this->getMockBuilder(MixedService::class)
			->setConstructorArgs(array(
				$this->getMockBuilder(EngineInterface::class)->getMockForAbstractClass(),
				array(),
				$this->getMockBuilder(PermissionResolverInterface::class)->getMockForAbstractClass(),
				$this->getMockBuilder(PersistenceHandlerInterface::class)->getMockForAbstractClass(),
				$this->getMockBuilder(NotificatorInterface::class)->getMockForAbstractClass(),
			))
			->getMockForAbstractClass()
		;
		
		$this->assertNotNull($mixedService);
	}
}

