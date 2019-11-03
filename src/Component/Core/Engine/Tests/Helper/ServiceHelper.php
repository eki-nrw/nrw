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

use Eki\NRW\Component\Base\Engine\Engine as EngineInterface;
use Eki\NRW\Component\Base\Engine\PermissionResolver as PermissionResolverInterface;
use Eki\NRW\Component\Base\Persistence\Handler as PersistenceHandlerInterface;
use Eki\NRW\Component\Notification\NotificatorInterface;
use Eki\NRW\Component\Core\Engine\BaseService;
use Eki\NRW\Component\Core\Engine\MixedService;
use Eki\NRW\Component\Core\Persistence\Handler as PersistenceHandler;

use Eki\NRW\Common\Res\Metadata\RegistryInterface;
use Eki\NRW\Component\Core\Persistence\Tests\Helper\HandlerHelper;
use Eki\NRW\Component\Core\Persistence\Tests\Helper\CommonHelper;
use Eki\NRW\Component\Core\Persistence\Tests\Helper\ArrayObjectHelper;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;

use PHPUnit\Framework\TestCase;

use ReflectionClass;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
final class ServiceHelper
{
	static public function createNullServiceSimple(
		TestCase $testCase,
		ObjectManager $objectManager = null,
		Cache $cache = null,
		array $methods = []
	)
	{
		$service = self::createBaseServiceSimple(
			$testCase,
			BaseService::class,
			[],
			[],
			array_merge(
				$methods,
				[ 'create', 'load', 'update', 'delete', 'getHandler' ]
			)
		);

		if ($objectManager === null)
		{
			$arrayObjects = [];
			$repositories = [];
			$objectManager = ArrayObjectHelper::createObjectManager($testCase, $arrayObjects, $repositories);
		}
		if ($cache === null)
			$cache = CommonHelper::createArrayCache();
		$handler = HandlerHelper::createNullPersistenceHandler($testCase, $objectManager, $cache);

		$service
			->expects($testCase->any())
			->method('getHandler')
			->will($testCase->returnCallback(function () use ($handler) {
				return $handler;
			}))
		;
		
		$service
			->expects($testCase->any())
			->method('create')
			->will($testCase->returnCallback(function ($identifier) use ($service) {
				$service->beginTransaction();
				try 
				{
					$handler = $service->getHandler();
					$res = $handler->create($identifier);
					
					$service->commit();
				}
				catch(\Exception $e)
				{
					$service->rollBack();
					throw $e;
				}
				
				return $res;
			}))
		;

		$service
			->expects($testCase->any())
			->method('load')
			->will($testCase->returnCallback(function ($id) use ($service) {
				$handler = $service->getHandler();
				return $handler->load($id);
			}))
		;

		$service
			->expects($testCase->any())
			->method('update')
			->will($testCase->returnCallback(function ($obj) use ($service) {
				$handler = $service->getHandler();
				$handler->update($obj);
			}))
		;

		$service
			->expects($testCase->any())
			->method('delete')
			->will($testCase->returnCallback(function ($obj) use ($service) {
				$handler = $service->getHandler();
				$handler->delete($obj);
			}))
		;
		
		return $service;
	}
	
	static public function createBaseServiceSimple(
		TestCase $testCase,
		$serviceClass,
		array $serviceSettings,
		array $extraArgs = [],
		array $methods = []
	)
	{
		return self::createBaseService(
			$testCase,
			$serviceClass,
			self::createSimpleEngine($testCase),
			$serviceSettings,
			PermissionResolverHelper::createNullPermissionResolver($testCase, true),
			self::getSimpleNotificator($testCase),
			$extraArgs,
			$methods
		);
	}
	
	static public function createBaseService(
		TestCase $testCase,
		$serviceClass,
		EngineInterface $engine,
		array $serviceSettings,
		PermissionResolverInterface $permissionResolver,
		NotificatorInterface $notificator,
		array $extraArgs = [],
		array $methods = []
	)
	{
		$constructorArgs = array(
			$engine,
			$serviceSettings,
			$permissionResolver,
			$notificator
		);
		foreach($extraArgs as $args)
		{
			$constructorArgs[] = $args;
		}

		$service = self::__createAService(
			$testCase,
			$serviceClass,
			$constructorArgs,
			$methods
		);
		
		return $service;
	}

	static public function createMixedService(
		TestCase $testCase,
		$serviceClass,
		EngineInterface $engine,
		array $serviceSettings,
		PermissionResolverInterface $permissionResolver,
		PersistenceHandlerInterface $persistenceHandler,
		NotificatorInterface $notificator,
		array $extraArgs = [],
		array $methods = []
	)
	{
		$constructorArgs = array(
			$engine,
			$serviceSettings,
			$permissionResolver,
			$notificator,
			$persistenceHandler
		);
		foreach($extraArgs as $args)
		{
			$constructorArgs[] = $args;
		}

		$service = self::__createAService(
			$testCase,
			$serviceClass,
			$constructorArgs,
			$methods
		);
		
		return $service;
	}

	static public function createServiceSimple(
		TestCase $testCase,
		$serviceClass,
		array $serviceSettings,
		$handlerClass,
		RegistryInterface $registry,
		array $extraArgs = [],
		array $serviceMethods = []
	)
	{
		$serviceMethods = array_merge($serviceMethods, ['getHandler']);
		
		$r = new ReflectionClass($serviceClass);
		if ($r->isSubclassOf(BaseService::class))
		{
			$handler = HandlerHelper::createPersistenceHandlerSimple(
				$testCase,
				$registry->getAll(),
				$handlerClass,
				[]
			);
		
			$service = self::createBaseServiceSimple(
				$testCase,
				$serviceClass,
				$serviceSettings,
				array_merge([$handler], $extraArgs),
				$serviceMethods
			);
		}
		else if ($r->isSubclassOf(MixedService::class))
		{
			$handler = HandlerHelper::createPersistenceHandlerSimple(
				$testCase,
				$registry->getAll(),
				$handlerClass,
				$serviceMethods
			);
			
			$service = self::createMixedServiceSimple(
				$testCase,
				$serviceClass,
				$serviceSettings,
				$handler,
				$extraArgs,
				[]
			);
		}
		else
			throw new \InvalidArgumentException("LJLJLJLJLJLJL");
			
		$service
			->expects($testCase->any())
			->method('getHandler')
			->will($testCase->returnCallback(function () use($handler) {
				return $handler;
			}))
		;
		
		return $service;
	}

	static public function createSimpleEngine(TestCase $testCase, array $methods = [])
	{
		$engine = $testCase->getMockBuilder(EngineInterface::class)
			->setMethods($methods)
			->getMockForAbstractClass()
		;
		
		return $engine;
	}
	
	static public function getSimpleNotificator(TestCase $testCase)
	{
		$notificator = $testCase->getMockBuilder(NotificatorInterface::class)
			->getMockForAbstractClass()
		;
		
		return $notificator;
	}

	static private function __createAService(
		TestCase $testCase,
		$serviceClass,
		array $constructorArgs,
		array $methods = []
	)
	{
		$methods = array_merge($methods, ['beginTransaction', 'commit', 'rollback']);

		$service = $testCase->getMockBuilder($serviceClass)
			->setMethods($methods)
			->setConstructorArgs($constructorArgs)
			->getMockForAbstractClass()
		;
		
		$service
			->expects($testCase->any())
			->method('beginTransaction')
			->will($testCase->returnCallback(function () {
				echo "begin transaction........\n";	
			}))
		;
		
		$service
			->expects($testCase->any())
			->method('commit')
			->will($testCase->returnCallback(function () {
				echo "commit........\n";	
			}))
		;

		$service
			->expects($testCase->any())
			->method('rollback')
			->will($testCase->returnCallback(function () {
				echo "rollback........\n";	
			}))
		;
		
		return $service;
	}
}
