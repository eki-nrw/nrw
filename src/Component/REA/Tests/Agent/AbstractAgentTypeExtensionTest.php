<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Agent;

use Eki\NRW\Mdl\REA\Agent\AgentTypeExtensionInterface;
use Eki\NRW\Common\Extension\ExtensionInterface;
use Eki\NRW\Common\Extension\BuildInterface;
use Eki\NRW\Mdl\REA\Agent\AbstractAgentTypeExtension;
use Eki\NRW\Mdl\REA\Agent\AgentTypeInterface;

use PHPUnit\Framework\TestCase;

class AbstractAgentTypeExtensionTest extends TestCase
{
    public function testInterfaces()
    {
    	$extension = $this->getMockBuilder(AbstractAgentTypeExtension::class)
    		->getMockForAbstractClass()
    	;
    	
    	$this->assertInstanceOf(AgentTypeExtensionInterface::class, $extension);
    	$this->assertInstanceOf(ExtensionInterface::class, $extension);
    	$this->assertInstanceOf(BuildInterface::class, $extension);
    }

    public function testExtensionSupportAgentType()
    {
    	$extension = $this->getMockBuilder(AbstractAgentTypeExtension::class)
    		->getMockForAbstractClass()
    	;

    	$subject = $this->getMockBuilder(AgentTypeInterface::class)->getMockForAbstractClass();
    	
    	$this->assertTrue($extension->support($subject));
    }
}
