<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper;

use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Metadata\RegistryInterface;
use Eki\NRW\Common\Res\Metadata\Registry;

use Eki\NRW\Component\SPBase\Persistence\Handler as PersistenceHandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\GroupHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Handler;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Fixtures\Res;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;
use PHPUnit\Framework\TestCase;

use stdClass;
use ReflectionClass;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
final class HandlerHelper
{
	static public function createNullPersistenceHandler(
		TestCase $testCase,
		ObjectManager $objectManager,
		Cache $cache
	)
	{
		$metadata = new Metadata(
			"testing", 
			array(
				'default' => Res::class,
				'std' => Res::class,
				'def' => Res::class
			),
			array(
				'cache_prefix' => 'testing',
				'cache_tag' => 'testing'
			)
		);
		
		$handler = self::createBasePersistenceHandler(
			$testCase, $objectManager, $cache, $metadata, 
			BaseHandler::class,
			['create', 'load', 'delete', 'update']
		);

		$handler
			->expects($testCase->any())
			->method('create')
			->will($testCase->returnCallback(function ($identifier) use ($handler) {
				$meta = $handler->getMetadata();
				$cl = $meta->getClass($identifier);
				$obj = new $cl();
				$handler->update($obj);
				return $obj;
			}))
		;

		$handler
			->expects($testCase->any())
			->method('load')
			->will($testCase->returnCallback(function ($id) use ($handler) {
				$r = new ReflectionClass($handler);
				
				$m = $r->getMethod('getCacheItem');
				$m->setAccessible(true);
				$cacheItem = $m->invokeArgs($handler, [$id]);
				if ($cacheItem->isHit())
					return $cacheItem->get();

				$m = $r->getMethod('findRes');
				$m->setAccessible(true);
				$loadedObj = $m->invokeArgs($handler, [$id]);
				if (null === $loadedObj)
		            throw new \RuntimeException("Object with id $id not found");

				$m = $r->getMethod('setCacheItem');
				$m->setAccessible(true);
				$cacheItem = $m->invokeArgs($handler, [$loadedObj]);
				
				return $loadedObj;
			}))
		;

		$handler
			->expects($testCase->any())
			->method('delete')
			->will($testCase->returnCallback(function ($obj) use ($handler) {
				$r = new ReflectionClass($handler);
				$m = $r->getMethod('deleteCacheItem');
				$m->setAccessible(true);
				$m->invokeArgs($handler, [$obj]);
				$handler->getObjectManager()->remove($obj);
			}))
		;

		$handler
			->expects($testCase->any())
			->method('update')
			->will($testCase->returnCallback(function ($obj) use ($handler) {
				$r = new ReflectionClass($handler);
				$m = $r->getMethod('setCacheItem');
				$m->setAccessible(true);
				$m->invokeArgs($handler, [$obj, null]);
				$handler->getObjectManager()->persist($obj);
			}))
		;

		
		return $handler;
	}

	static public function createBasePersistenceHandlerSimple(
		TestCase $testCase,
		MetadataInterface $metadata,
		$handlerClass,
		array $methods,
		array $extraArgs = []
	)
	{
		$arrayObjects = [];
		$repositories = [];
		$objectManager = ArrayObjectHelper::createObjectManager($testCase, $arrayObjects, $repositories);
		$cache = CommonHelper::createArrayCache();

		return self::createBasePersistenceHandler(
			$testCase,
			$objectManager,
			$cache,
			$metadata,
			$handlerClass,
			$methods,
			$extraArgs
		);
	}

	static public function createBasePersistenceHandler(
		TestCase $testCase,
		ObjectManager $objectManager,
		Cache $cache,
		MetadataInterface $metadata,
		$handlerClass,
		array $methods,
		array $extraArgs = []
	)
	{
		if (
			$handlerClass !== BaseHandler::class 
			and 
			!(new ReflectionClass($handlerClass))->isSubclassOf(BaseHandler::class)
		)
			throw new \InvalidArgumentException(sprintf(
				"Parameter 'handlerClass' must be subclass of %s.",
				BaseHandler::class
			));

		$constructorArgs = array(
			$objectManager,
			$cache,
			$metadata
		);
		foreach($extraArgs as $arg)
		{
			$constructorArgs[] = $arg;
		}

		$handler = self::__createAHandler(
			$testCase, 
			$handlerClass, 
			$constructorArgs,
			array_merge(array('getMetadata'), $methods)
		);
		
		$handler
			->expects($testCase->any())
			->method('getMetadata')
			->will($testCase->returnCallback(function () use ($handler) {
				$r = new ReflectionClass($handler);
				$p = $r->getProperty('metadata');
				$p->setAccessible(true);
				return $p->getValue($handler);
			}))
		;
		
		return $handler;
	}

	static public function createGroupPersistenceHandlerSimple(
		TestCase $testCase,
		array $metadatas,
		$handlerClass,
		array $methods,
		array $extraArgs = []
	)
	{
		$arrayObjects = [];
		$repositories = [];
		$objectManager = ArrayObjectHelper::createObjectManager($testCase, $arrayObjects, $repositories);
		$cache = CommonHelper::createArrayCache();

		return self::createGroupPersistenceHandler(
			$testCase,
			$objectManager,
			$cache,
			$metadatas,
			$handlerClass,
			$methods,
			$extraArgs
		);
	}

	static public function createGroupPersistenceHandler(
		TestCase $testCase,
		ObjectManager $objectManager,
		Cache $cache,
		array $metadatas,
		$handlerClass,
		array $methods,
		array $extraArgs = []
	)
	{
		if (
			$handlerClass !== GroupHandler::class 
			and 
			!(new ReflectionClass($handlerClass))->isSubclassOf(GroupHandler::class)
		)
			throw new \InvalidArgumentException(sprintf(
				"Parameter 'handlerClass' must be subclass of %s.",
				GroupHandler::class
			));

		$constructorArgs = array(
			$objectManager,
			$cache,
			$metadatas
		);
		foreach($extraArgs as $arg)
		{
			$constructorArgs[] = $arg;
		}

		$handler = self::__createAHandler(
			$testCase, 
			$handlerClass, 
			$constructorArgs,
			array_merge(array('getRegistry'), $methods)
		);
		
		$handler
			->expects($testCase->any())
			->method('getRegistry')
			->will($testCase->returnCallback(function () use ($handler) {
				$r = new ReflectionClass($handler);
				$p = $r->getProperty('registry');
				$p->setAccessible(true);
				return $p->getValue($handler);
			}))
		;
		
		return $handler;
	}

	static public function createPersistenceHandlerSimple(
		TestCase $testCase,
		array $metadatas,
		$handlerClass,
		array $methods
	)
	{
		$r = new ReflectionClass($handlerClass);
		if ($r->isSubclassOf(BaseHandler::class))
		{
			$handler = self::createBasePersistenceHandlerSimple(
				$testCase,
				reset($metadatas),
				$handlerClass,
				$methods
			);
		}
		else if($r->isSubclassOf(GroupHandler::class))
		{
			$handler = self::createGroupPersistenceHandlerSimple(
				$testCase,
				$metadatas,
				$handlerClass,
				$methods
			);
		}
		else
		{
			throw new \InvalidArgumentException("MMMMMMMMMMMMMMMM");
		}
			
		return $handler;
	}

	static private function __createAHandler(
		TestCase $testCase,
		$handlerClass,
		array $constructorArgs,
		array $methods
	)
	{
		$handler = $testCase->getMockBuilder($handlerClass)
			->setMethods(array_merge($methods, ['getObjectManager', 'getCache']))
			->setConstructorArgs($constructorArgs)
			->getMockForAbstractClass()
		;
		
		$handler
			->expects($testCase->any())
			->method('getObjectManager')
			->will($testCase->returnCallback(function () use ($handler) {
				$r = new ReflectionClass($handler);
				$p = $r->getProperty('objectManager');
				$p->setAccessible(true);
				return $p->getValue($handler);
			}))
		;

		$handler
			->expects($testCase->any())
			->method('getCache')
			->will($testCase->returnCallback(function () use ($handler) {
				$r = new ReflectionClass($handler);
				$p = $r->getProperty('cache');
				$p->setAccessible(true);
				return $p->getValue($handler);
			}))
		;

		return $handler;
	}
}

