<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Resource\Extension\Core\Type;

use Eki\NRW\Mdl\REA\Resource\Extension\Core\Type\MaterialResourceType;

use PHPUnit\Framework\TestCase;

class MaterialResourceTypeTest extends TestCase
{
	public function testResourceTypeSpecified()
	{
		$resourceType = $this->createResourceType();
		
		$this->assertSame('material', $resourceType->getResourceType());
	}

	private function createResourceType()
	{
    	$resourceType = $this->getMockBuilder(MaterialResourceType::class)->getMockForAbstractClass();
    	
    	return $resourceType;
	}
}
