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

use Eki\NRW\Common\Res\Metadata\RegistryInterface;
use Eki\NRW\Common\Res\Metadata\Registry;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\HandlerHelper;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Cache\ArrayCache;
use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;

abstract class BaseGroupHandlerTest extends BaseAbstractHandlerTest
{
	/**
	* @var \Eki\NRW\Common\Res\Metadata\RegistryInterface
	*/
	protected $registry;

    protected function addToRegistry($alias, array $configuration)
    {
    	if ($this->registry === null)
			$this->registry = new Registry();
			
		$this->registry->addFrom($alias, $configuration);
		
		return $this->registry;
	}
	
	protected function getMetadatas()
	{
    	if ($this->registry === null)
    		throw new \Exception("You must 'addToRegistry' before.");
    		
    	return $this->registry->getAll();
	}
	
	public function tearDown()
	{
		$this->registry = null;
	}
	
	protected function createGroupPersistenceHandler(
		ObjectManager $objectManager,
		Cache $cache,
		array $metadatas,
		$handlerClass,
		array $methods = [],
		array $extraArgs = []
	)
	{
		$constructorArgs = array(
			$objectManager,
			$cache,
			$metadatas
		);
		foreach($extraArgs as $arg)
		{
			$constructorArgs[] = $arg;
		}

		$handler = $this->createHandlerMock(
			$handlerClass, 
			$constructorArgs,
			array_merge(array('getRegistry'), $methods)
		);
		
		$handler
			->expects($this->any())
			->method('getRegistry')
			->will($this->returnCallback(function () use ($handler) {
				$r = new ReflectionClass($handler);
				$p = $r->getProperty('registry');
				$p->setAccessible(true);
				return $p->getValue($handler);
			}))
		;
		
		return $handler;
	}
}
