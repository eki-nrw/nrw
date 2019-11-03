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

use Eki\NRW\Component\SPBase\Persistence\Handler as PersistenceHandlerInterface;
use Eki\NRW\Component\SPBase\Persistence\Networking\Handler as NetworkingHandler;
use Eki\NRW\Component\SPBase\Persistence\Resourcing\Handler as ResourcingHandler;
use Eki\NRW\Component\SPBase\Persistence\Relating\Handler as RelatingHandler;
use Eki\NRW\Component\SPBase\Persistence\TransactionHandler;

use Eki\NRW\Common\Res\Metadata\Metadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use PHPUnit\Framework\TestCase;

final class SimpleHelper
{
    static public function createObjectManager(TestCase $testCase)
    {
		$objectManager = $testCase
			->getMockBuilder(ObjectManager::class)
			->getMockForAbstractClass()
		;
		
		return $objectManager;
	}
	
	static public function createCache(TestCase $testCase)
	{
		$cache = $testCase
			->getMockBuilder(Cache::class)
			->getMockForAbstractClass()
		;
		
		return $cache;
	}

    static public function createSimplePersistenceHandler(TestCase $testCase)
    {
    	$handler = $testCase
    		->getMockBuilder(PersistenceHandlerInterface::class)
    		->setMethods([
    			'networkingHandler', 'resourcingHandler', 
    			'relatingHanfler',
    			'transactionHandler'
    		])
    		->getMockForAbstractClass()
    	;
    	
    	$handler
    		->expects($testCase->any())
    		->method('networkingHandler')
    		->will($testCase->returnCallback(function () use ($testCase) {
    			return $testCase
    				->getMockBuilder(NetworkingHandler::class)
    				->getMockForAbstractClass()
    			;
    		}))
    	;

    	$handler
    		->expects($testCase->any())
    		->method('resourcingHandler')
    		->will($testCase->returnCallback(function () use ($testCase) {
    			return $testCase
    				->getMockBuilder(ResourcingHandler::class)
    				->getMockForAbstractClass()
    			;
    		}))
    	;

    	$handler
    		->expects($testCase->any())
    		->method('relatingHandler')
    		->will($testCase->returnCallback(function () use ($testCase) {
    			return $testCase
    				->getMockBuilder(RelatingHandler::class)
    				->getMockForAbstractClass()
    			;
    		}))
    	;

    	$handler
    		->expects($testCase->any())
    		->method('transactionHandler')
    		->will($testCase->returnCallback(function () use ($testCase) {
    			return $testCase
    				->getMockBuilder(TransactionHandler::class)
    				->getMockForAbstractClass()
    			;
    		}))
    	;
    	
    	return $handler;
	}
	
    static public function createBaseHandler(TestCase $testCase, $resIdentifier, $resClasses, array $resParameters)
    {
    	$handler = $testCase
    		->getMockBuilder(BaseHandler::class)
    		->setConstructorArgs(array(
    			self::createObjectManager(),
    			self::createCache(),
    			new Metadata($resIdentifier, $resClasses, $resParameters)
    		))
    		->getMockForAbstractClass()
    	;
    	
    	return $handler;
	}
}

