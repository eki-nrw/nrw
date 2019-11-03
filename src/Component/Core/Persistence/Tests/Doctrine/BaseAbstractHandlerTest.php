<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine;

use Symfony\Component\Cache\Adapter\ArrayAdapter;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Cache\ArrayCache;
use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;

use ReflectionClass;

abstract class BaseAbstractHandlerTest extends BaseTest
{
	protected $arrayObjects;
	protected $repositories;
	
	protected function createObjectManager()
	{
		$this->arrayObjects = [];
		$this->repositories = [];
		return ArrayObjectHelper::createObjectManager($this, $this->arrayObjects, $this->repositories);
	}
	
	protected function createCache()
	{
		return new ArrayAdapter();
	}

	protected function createHandlerMock(
		$handlerClass,
		array $constructorArgs,
		array $methods
	)
	{
		$handler = $this->getMockBuilder($handlerClass)
			->setMethods(array_merge($methods, ['getObjectManager', 'getCache']))
			->setConstructorArgs($constructorArgs)
			->getMockForAbstractClass()
		;
		
		$handler
			->expects($this->any())
			->method('getObjectManager')
			->will($this->returnCallback(function () use ($handler) {
				$r = new ReflectionClass($handler);
				$p = $r->getProperty('objectManager');
				$p->setAccessible(true);
				return $p->getValue($handler);
			}))
		;

		$handler
			->expects($this->any())
			->method('getCache')
			->will($this->returnCallback(function () use ($handler) {
				$r = new ReflectionClass($handler);
				$p = $r->getProperty('cache');
				$p->setAccessible(true);
				return $p->getValue($handler);
			}))
		;

		return $handler;
	}
}
