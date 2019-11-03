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

use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Common\Res\Metadata\Metadata;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Cache\ArrayCache;
use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;

use ReflectionClass;

abstract class BaseBaseHandlerTest extends BaseAbstractHandlerTest
{
	protected function configMetadata($alias, array $classes, array $parameters)
	{
		return new Metadata($alias, $classes, $parameters);
	}
	
	protected function createBasePersistenceHandler(
		ObjectManager $objectManager,
		Cache $cache,
		MetadataInterface $metadata,
		$handlerClass,
		array $methods = [],
		array $extraArgs = []
	)
	{
		$constructorArgs = array(
			$objectManager,
			$cache,
			$metadata
		);
		foreach($extraArgs as $arg)
		{
			$constructorArgs[] = $arg;
		}

		$handler = $this->createHandlerMock(
			$handlerClass, 
			$constructorArgs,
			array_merge(array('getMetadata'), $methods)
		);
		
		$handler
			->expects($this->any())
			->method('getMetadata')
			->will($this->returnCallback(function () use ($handler) {
				$r = new ReflectionClass($handler);
				$p = $r->getProperty('metadata');
				$p->setAccessible(true);
				return $p->getValue($handler);
			}))
		;
		
		return $handler;
	}
}

