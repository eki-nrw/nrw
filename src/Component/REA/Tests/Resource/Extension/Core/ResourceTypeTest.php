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

use Eki\NRW\Mdl\REA\Resource\Extension\Core\ResourceType;

use PHPUnit\Framework\TestCase;

class ResourceTypeTest extends TestCase
{
	public function testResourceTypeSpecified()
	{
		$resourceType = $this->createResourceType();
		
		$this->assertNotEmpty($resourceType->getResourceType());
	}

	private function createResourceType()
	{
    	$resourceType = $this->getMockBuilder(ResourceType::class)->getMockForAbstractClass();
    	
    	return $resourceType;
	}
}
