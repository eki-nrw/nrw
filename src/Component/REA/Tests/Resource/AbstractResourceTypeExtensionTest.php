<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Resource;

use Eki\NRW\Mdl\REA\Resource\ResourceTypeExtensionInterface;
use Eki\NRW\Common\Extension\ExtensionInterface;
use Eki\NRW\Common\Extension\BuildInterface;
use Eki\NRW\Mdl\REA\Resource\AbstractResourceTypeExtension;
use Eki\NRW\Mdl\REA\Resource\ResourceTypeInterface;

use PHPUnit\Framework\TestCase;

class AbstractResourceTypeExtensionTest extends TestCase
{
    public function testInterfaces()
    {
    	$extension = $this->getMockBuilder(AbstractResourceTypeExtension::class)
    		->getMockForAbstractClass()
    	;
    	
    	$this->assertInstanceOf(ResourceTypeExtensionInterface::class, $extension);
    	$this->assertInstanceOf(ExtensionInterface::class, $extension);
    	$this->assertInstanceOf(BuildInterface::class, $extension);
    }

    public function testExtensionSupportResourceType()
    {
    	$extension = $this->getMockBuilder(AbstractResourceTypeExtension::class)
    		->getMockForAbstractClass()
    	;

    	$subject = $this->getMockBuilder(ResourceTypeInterface::class)->getMockForAbstractClass();
    	
    	$this->assertTrue($extension->support($subject));
    }
}
