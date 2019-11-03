<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Resource\Extension\Core;

use Eki\NRW\Mdl\REA\Resource\Extension\Core\Resource;
use Eki\NRW\Mdl\REA\Resource\ResourceTypeInterface;

use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
	public function testDummy()
	{
		$this->assertTrue(true);
	}
	
	private function createResource()
	{
    	$resource = $this->getMockBuilder(Resource::class)->getMockForAbstractClass();
    	
    	return $resource;
	}
	
	private function createResourceType($isPerson = true)
	{
		$resourceType = $this->getMockBuilder(ResourceTypeInterface::class)
			->setMethods(['isPersonType'])
			->getMockForAbstractClass()
		;
		
		$resourceType->expects($this->any())
			->method('isPersonType')
			->will($this->returnValue($isPerson))
		;
		
		return $resourceType;
	}
}
