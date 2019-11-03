<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Tests\Service\Mock;

use Eki\NRW\Component\Base\Engine\PermissionResolver;

use Eki\NRW\Component\Core\Engine\Tests\Service\Mock\Base as BaseServiceMockTest;
use Eki\NRW\Component\Core\Engine\NetworkingService;

use Eki\NRW\Component\REA\Agent\AgentTypeBuilder;
use Eki\NRW\Component\REA\Agent\AgentBuilder;
use Eki\NRW\Component\Networking\Agent\AgentInterface;
use Eki\NRW\Component\Networking\Agent\Agent;
use Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface;
use Eki\NRW\Component\Networking\Agent\Type\AgentType;

/**
* Networking Service Test.
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class NetworkingServiceTest extends BaseServiceMockTest
{
	public function testConstructor()
	{
		$engineMock = $this->getEngineMock();
        $networkingHandlerMock = $this->getPersistenceComponentHandlerMock("Networking\\Handler");
        $settings = [];

		$pluginsMock = $this->getPluginsMock(array(
			'agentTypeBuilder' => $this->getAgentTypeBuilder(),
			'agentBuilder' => $this->getAgentBuilder()
		));
		
		$service = new NetworkingService(
			$engineMock,
			$settings,
			$networkingHandlerMock,
			$pluginsMock->getPlugin('agentTypeBuilder'),
			$pluginsMock->getPlugin('agentBuilder')
		);
		
        $this->assertAttributeSame(
            $engineMock,
            'engine',
            $service
        );
        
        $this->assertAttributeSame(
            $networkingHandlerMock,
            'networkingHandler',
            $service
        );

        $this->assertAttributeSame(
            $settings,
            'settings',
            $service
        );

        $this->assertAttributeSame(
            $this->getAgentTypeBuilder(),
            'agentTypeBuilder',
            $service
        );

        $this->assertAttributeSame(
            $this->getAgentBuilder(),
            'agentBuilder',
            $service
        );
	}

	public function testLoadAgentType()
	{
		$engine = $this->getEngineMock();
//		$networkingServiceMock = $this->getPartlyMockedNetworkingService(array('loadAgentType'));
		$networkingServiceMock = $this->getPartlyMockedNetworkingService();
        
		$agentTypeId = 99;
        $result = $networkingServiceMock->loadAgentType($agentTypeId);
        
        $this->assertNotNull($result);
	}

	/**
	* @var \Eki\NRW\Component\REA\Agent\AgentTypeBuilder
	*/
	protected $agentTypeBuilder;
	
	protected function getAgentTypeBuilder()
	{
		if (!isset($this->agentTypeBuilder))
		{
			$this->agentTypeBuilder = new AgentTypeBuilder('base');
		}
		
		return $this->agentTypeBuilder;
	}

	/**
	* @var \Eki\NRW\Component\REA\Agent\AgentBuilder
	*/
	protected $agentBuilder;
	
	protected function getAgentBuilder()
	{
		if (!isset($this->agentBuilder))
		{
			$this->agentBuilder = new AgentBuilder($this->createMock(AgentTypeInterface::class));
		}
		
		return $this->agentBuilder;
	}

    /**
     * @var \Eki\NRW\Component\Core\Engine\NetworkingService
     */
    protected $partlyMockedContentService;

    /**
     * Returns the networking service to test with $methods mocked.
     *
     * Injected Engine comes from {@see getEngineMock()} and persistence handler from {@see getPersistenceMock()}
     *
     * @param string[] $methods
     *
     * @return \Eki\NRW\Component\Core\Engine\NetworkingService|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPartlyMockedNetworkingService(array $methods = array())
    {
        if (!isset($this->partlyMockedNetworkingService)) 
        {
        	$engineMock = $this->getEngineMock();
	        $networkingHandlerMock = $this->getPersistenceComponentHandlerMock("Networking\\Handler");
			$pluginsMock = $this->getPluginsMock(array(
				'agentTypeBuilder' => $this->getAgentTypeBuilder(),
				'agentBuilder' => $this->getAgentBuilder()
			));
			
            $this->partlyMockedNetworkingService = $this->getMockBuilder(NetworkingService::class)
                ->setMethods($methods)
                ->setConstructorArgs(
                    array(
                        $engineMock,
                        array($methods),
                        $networkingHandlerMock,
						$pluginsMock->getPlugin('agentTypeBuilder'),
						$pluginsMock->getPlugin('agentBuilder')
                    )
                )
                ->getMock()
            ;
        }

        return $this->partlyMockedNetworkingService;
    }
}
