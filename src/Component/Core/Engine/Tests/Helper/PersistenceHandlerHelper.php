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

use Eki\NRW\Component\Base\Persistence\Handler as PersistenceHandlerInterface;
use Eki\NRW\Component\Core\Persistence\Handler as PersistenceHandler;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Metadata\Registry;

use Eki\NRW\Component\Core\Persistence\Tests\Helper\CommonHelper;
use Eki\NRW\Component\Core\Persistence\Tests\Helper\ArrayObjectHelper;

use PHPUnit\Framework\TestCase;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class PersistenceHandlerHelper
{
	public function createSimplePersistenceHandler(TestCase $testCase, array $methods = [])
	{
		$methods = array_merge($methods, ['getObjectManager', 'getCache']);
		
		$handler = $testCase->getMockBuilder(PersistenceHandlerInterface::class)
			->setMethods($methods)
			->getMockForAbstractClass()
		;
		
		$arrayObjects = [];
		$repositories = [];
		$objectManager = ArrayObjectHelper::createObjectManager($testCase, $arrayObjects, $repositories);
		$cache = CommonHelper::createArrayCache();

		$handler
			->expects($testCase->any())
			->method('getObjectManager')
			->will($testCase->returnCallback( function () use (&$objectManager) {
				return $objectManager;
			} ))
		;

		$handler
			->expects($testCase->any())
			->method('getCache')
			->will($testCase->returnCallback( function () use (&$cache) {
				return $cache;
			} ))
		;
		
		return $handler;
	}

	public function createBasePersistenceHandler(
		TestCase $testCase,
		$baseHandlerClass,
		array $configurations = [], 
		array $methods = []
	)
	{
		$arrayObjects = [];
		$repositories = [];
		$objectManager = ArrayObjectHelper::createObjectManager($testCase, $arrayObjects, $repositories);
		$cache = CommonHelper::createArrayCache();

		$metadata = new Metadata($configuration['alias'], $configuration['classes'], $configuration['parameters']);

		$methods = array_merge($methods, []);
		$handler = $testCase->getMockBuilder($baseHandlerClass)
			->setMethods($methods)
			->setConstructorArgs(array(
				$objectManager,
				$cache,
				$metadata
			))
			->getMock()
		;
		
		return $handler;
	}

	public function createGroupPersistenceHandler(
		TestCase $testCase,
		$groupHandlerClass,
		array $configurations = [], 
		array $methods = []
	)
	{
		$arrayObjects = [];
		$repositories = [];
		$objectManager = ArrayObjectHelper::createObjectManager($testCase, $arrayObjects, $repositories);
		$cache = CommonHelper::createArrayCache();

		$registry = new Registry();
		foreach($configurations as $alias => $configuration)
		{
			$registry->addFrom($alias, $configuration);
		}

		$methods = array_merge($methods, []);
		$handler = $testCase->getMockBuilder($groupHandlerClass)
			->setMethods($methods)
			->setConstructorArgs(array(
				$objectManager,
				$cache,
				$registry
			))
			->getMock()
		;
		
		return $handler;
	}
}
