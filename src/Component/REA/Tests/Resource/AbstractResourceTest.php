<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Tests\Resource;

use Eki\NRW\Mdl\REA\Resource\AbstractResource;
use Eki\NRW\Mdl\REA\Resource\ResourceTypeInterface;

use Eki\NRW\Common\Location\LocationInterface;

use PHPUnit\Framework\TestCase;

class AbstractResourceTest extends TestCase
{
    public function testResourceType()
    {
    	$resource = $this->createResource();
    	
    	$resourceType = $this->createResourceType('a_resource_type_name');
    	$resource->setResourceType($resourceType);
    	
    	$this->assertNotNull($resource->getResourceType());
    }

    public function testResourceName()
    {
    	$resource = $this->createResource();

		// Default resource name is empty()
		$this->assertEmpty($resource->getResourceName());
		
		$resource->setResourceName('resource name');
		
		$this->assertSame($resource->getResourceName(), 'resource name');
    }
    
    public function testCurrentLocation()
    {
    	$resource = $this->createResource();
			
		// Default location is null
		$this->assertNull($resource->getCurrentLocation());

		// Sets a location		
		$location = $this->createLocation('a_location_name');
		$resource->setCurrentLocation($location);
		$this->assertNotNull($resource->getCurrentLocation());
		
		// Reset main location of resource
		$resource->setCurrentLocation();
		$this->assertNull($resource->getCurrentLocation());
	}
	
	private function createResourceType($typeName)
	{
    	$resourceType = $this->getMockBuilder(ResourceTypeInterface::class)
    		->setMethods('getResourceType')
    		->getMockForAbstractClass();
    		
    	$resourceType->expects($this->any())
    		->method('getResourceType')
    		->will($this->returnValue($typeName))
    	;	
    		
    	return $resourceType;
	}
	
	private function createLocation($locationName)
	{
    	$location = $this->getMockBuilder(LocationInterface::class)
    		->setMethods('getLocationName')
    		->getMockForAbstractClass();
    		
    	$resourceType->expects($this->any())
    		->method('getLocationName')
    		->will($this->returnValue($locationName))
    	;	
    		
    	return $location;
	}
	
	private function createResource()
	{
    	$resource = $this->getMockBuilder(AbstractResource::class)
    		->getMockForAbstractClass()
    	;
    	
    	return $resource;
	}
}
