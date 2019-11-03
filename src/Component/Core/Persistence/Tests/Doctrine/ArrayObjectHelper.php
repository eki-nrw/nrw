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

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use ArrayObject;

use PHPUnit\Framework\TestCase;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
final class ArrayObjectHelper
{
	/**
	* @internal
	* 
	*/
	static public function applyCriteria(array $resources, array $criteria, ArrayObject $arrayObject) 
	{
		$accessor = PropertyAccess::createPropertyAccessor();
        foreach ($arrayObject as $object) {
            foreach ($criteria as $criterion => $value) {
                if ($value !== $accessor->getValue($object, $criterion)) {
                    $key = array_search($object, $resources);
                    unset($resources[$key]);
                }
            }
        }

        return $resources;
	}
	
    static public function createObjectManager(
    	TestCase $testCase, 
    	array &$arrayObjects, array &$repositories, 
    	$omClassName = null
    )
    {
    	$mockBuilder = $testCase
			->getMockBuilder(ObjectManager::class);
		if ($omClassName !== null)
			$mockBuilder->setMockClassName($omClassName);

		$objectManager = $mockBuilder
			->setMethods([
				'acceptClassName', 'getArrayObjects', 'getArrayObjectOfClass', 
				'find', 'getRepository', 'remove', 'persist'
			])
			->getMockForAbstractClass()
		;
		
		$objectManager
			->expects($testCase->any())
			->method('acceptClassName')
			->will($testCase->returnCallback( 
				function ($className) use ($testCase, &$arrayObjects, &$repositories, &$objectManager) {
					if (!isset($arrayObjects[$className]))
						$arrayObjects[$className] = new ArrayObject();
					if (!isset($repositories[$className]))
						$repositories[$className] = self::createRepository($testCase, $className, $objectManager);
				}
			))
		;

		$objectManager
			->expects($testCase->any())
			->method('getArrayObjects')
			->will($testCase->returnCallback( function () use (&$arrayObjects) {
				return $arrayObjects; 
			}))
		;

		$objectManager
			->expects($testCase->any())
			->method('getArrayObjectOfClass')
			->will($testCase->returnCallback( function ($class) use (&$arrayObjects) {
				return $arrayObjects[$class]; 
			}))
		;
		
		$objectManager
			->expects($testCase->any())
			->method('find')
			->will($testCase->returnCallback( function ($className, $id) use (&$arrayObjects, &$objectManager) {
		    	$objectManager->acceptClassName($className);
		    	$arrObjects = $objectManager->getArrayObjects();
		        $results = self::applyCriteria(
		        	$arrObjects[$className]->getArrayCopy(), 
		        	array('id' => $id), 
		        	$arrObjects[$className]
		        );
		        if ($result = reset($results)) {
		            return $result;
		        }

		        return null;
			}))
		;
		
		$objectManager
			->expects($testCase->any())
			->method('getRepository')
			->will($testCase->returnCallback( function ($className) use ($testCase, &$objectManager, &$repositories) {
				$objectManager->acceptClassName($className);
				return $repositories[$className];
			}))
		;

		$objectManager
			->expects($testCase->any())
			->method('persist')
			->will($testCase->returnCallback( function ($obj) use (&$arrayObjects, $objectManager) {
		    	$className = get_class($obj);
		    	$objectManager->acceptClassName($className);
		    	$allObjects = $arrayObjects[$className]->getArrayCopy();

		        if (in_array($obj, $allObjects))
		        	return;
		    	
				$accessor = PropertyAccess::createPropertyAccessor();
				if (empty($gotValue = $accessor->getValue($obj, "id")))
				{
					$accessor->setValue($obj, "id", spl_object_hash($obj));
				}

				$arrayObjects[$className]->append($obj);
			}))
		;

		$objectManager
			->expects($testCase->any())
			->method('remove')
			->will($testCase->returnCallback( function ($obj) use (&$arrayObjects) {
		    	$className = get_class($obj);
		    	if (!isset($arrayObjects[$className]))
		    		return false;
		        $newObj = array_filter($arrayObjects[$className]->getArrayCopy(), function ($object) use ($obj) {
		            return $object !== $obj;
		        });
		        $arrayObjects[$className]->exchangeArray($newObj);
			}))
		;
		
		return $objectManager;
	}
	
	static public function createRepository(TestCase $testCase, $className, ObjectManager $objectManager)
	{
	    $applyOrder = function (array $resources, array $orderBy)
	    {
	        $results = $resources;

			$accessor = PropertyAccess::createPropertyAccessor();
	        foreach ($orderBy as $property => $order) {
	            $sortable = [];

	            foreach ($results as $key => $object) {
	                $sortable[$key] = $accessor->getValue($object, $property);
	            }

	            if ('asc' === strtolower($order)) {
	                asort($sortable);
	            }
	            if ('dsc' === strtolower($order)) {
	                arsort($sortable);
	            }

	            $results = [];

	            foreach ($sortable as $key => $propertyValue) {
	                $results[$key] = $resources[$key];
	            }
	        }

	        return $results;
	    };
		
		$repository = $testCase
			->getMockBuilder(ObjectRepository::class)
			->setMethods([
				'find', 'findAll', 'findBy', 'findOneBy',
				'getClassName', 'getArrayObject'
			])
			->getMockForAbstractClass()
		;

		$repository
			->expects($testCase->any())
			->method('find')
			->will($testCase->returnCallback( function ($id) use ($repository) {
				return $repository->findOneBy(array('id' => $id));
			}))
		;

		$repository
			->expects($testCase->any())
			->method('findAll')
			->will($testCase->returnCallback( 
				function ()	use ($className, $objectManager) 
				{
					$objectManager->acceptClassName($className);
					$arrayObject = $objectManager->getArrayObjectOfClass($className);
			        $results = $arrayObject->getArrayCopy();
			        
			        return $results;
				}
			))
		;
		
		$repository
			->expects($testCase->any())
			->method('findBy')
			->will($testCase->returnCallback( 
				function (array $criteria, array $orderBy = null, $limit = null, $offset = null) 
					use ($repository, $className, $objectManager, $applyOrder) 
				{
					$arrayObject = $repository->getArrayObject();
					$results = $repository->findAll();					

			        if (!empty($criteria)) 
			        {
				        $results = self::applyCriteria(
				        	$results, 
				        	$criteria, 
				        	$arrayObject
				        );
			        }

			        if (!empty($orderBy)) {
			            $results = $applyOrder($results, $orderBy);
			        }

			        $results = array_slice($results, $offset, $limit);

			        return $results;
				}
			))
		;

		$repository
			->expects($testCase->any())
			->method('findOneBy')
			->will($testCase->returnCallback( function (array $criteria) use ($className, $objectManager) {
		        if (empty($criteria)) {
		            $testCase->throwException(new \InvalidArgumentException('The criteria array needs to be set.'));
		        }

				$objectManager->acceptClassName($className);
				$arrayObjects = $objectManager->getArrayObjects();

		        $results = self::applyCriteria(
		        	$arrayObjects[$className]->getArrayCopy(), 
		        	$criteria, 
		        	$arrayObjects[$className]
		        );
	        
		        if ($result = reset($results)) {
		            return $result;
		        }

		        return null;
			}))
		;

		$repository
			->expects($testCase->any())
			->method('getClassName')
			->will($testCase->returnCallback( function () use ($className) {
				return $className;
			}))
		;

		$repository
			->expects($testCase->any())
			->method('getArrayObject')
			->will($testCase->returnCallback( 
				function ()	use ($repository, $objectManager) 
				{
					return $objectManager->getArrayObjectOfClass($repository->getClassName());
				}
			))
		;

		return $repository;
	}
}

