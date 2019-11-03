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

use Eki\NRW\Component\Base\Engine\Engine as EngineInterface;
use Eki\NRW\Component\Base\Engine\PermissionResolver;
use Eki\NRW\Component\Base\Engine\Plugins as PluginsInterface;

use Eki\NRW\Component\SPBase\Persistence\Handler;

use Eki\NRW\Component\Core\Engine\Engine;
use Eki\NRW\Component\Core\Engine\Plugins;

use Eki\NRW\Component\Notification\NotificatorInterface;

use PHPUnit\Framework\TestCase;

//use stdClass;

/**
* Base test case for tests on services using Mock testing.
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class Base extends TestCase
{
	/**
	* @var \Eki\NRW\Component\Base\Engine
	*/
	private $engine;

    /**
     * Get real engine with mocked dependencies.
     *
     * @param array $serviceSettings If set then non shared instance of Engine is returned
     *
     * @return \Eki\NRW\Component\Base\Engine\Engine
     */
	protected function getEngine(array $serviceSettings = array())
	{
		if ($this->engine === null || !empty($serviceSettings))
		{
			$eninge = new Engine(
				"default_engine_name",
				$serviceSettings,
				$this->getPersistenceMock(),
				$this->getPluginsMock()
			);
			
			if (!empty($serviceSettings))
				return $engine;
				
			$this->engine = $engine;
		}
		
		return $this->engine;
	}

    /**
     * @return \Eki\\NRW\\Component\\Base\\Engine\\Engine|\PHPUnit\Framework\MockObject\MockObject
     */
    private $engineMock;

    /**
     * @return \Eki\\NRW\\Component\\Base\\Engine\\Engine|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getEngineMock(array $methods = [])
    {
        if ($this->engineMock === null || !empty($methods)) 
        {
            $engineMock = $this->createMock(EngineInterface::class);
            
            $permissionResolver = $this->createMock(PermissionResolver::class);
            $permissionResolver->expects($this->any())
            	->method('hasAccess')
            	->will($this->returnValue(true))
            ;
            $permissionResolver->expects($this->any())
            	->method('canUser')
            	->will($this->returnValue(true))
            ;
            
            $engineMock->expects($this->any())
            	->method('getPermissionResolver')
            	->will($this->returnValue($permissionResolver))
            ;
            
            if (!empty($methods))
            	return $engineMock;
            
            $this->engineMock = $engineMock;
        }

        return $this->engineMock;
    }

	/**
	* @var \Eki\NRW\Component\Base\Persistence\Handler|\PHPUnit\Framework\MockObject\MockObject
	*/
	private $persistenceMock;

	protected function getPersistenceMock()
	{
		if (!isset($this->persistenceMock))
		{
			$this->persistenceMock = $this->createMock(Handler::class);

			$this->persistenceMock->expects($this->any())
				->method('networkingHandler')
				->will($this->returnValue($this->getPersistenceMockHandler('Networking\\Handler')))
			;

			$this->persistenceMock->expects($this->any())
				->method('resourcingHandler')
				->will($this->returnValue($this->getPersistenceMockHandler('Resourcing\\Handler')))
			;
		}
		
		return $this->persistenceMock;
	}

    /**
     * The Content / Location / Search ... handlers for the persistence / Search / .. handler mocks.
     *
     * @var \PHPUnit\Framework\MockObject\MockObject[] Key is relative to "\Eki\NRW\Component\SPBase\"
     *
     * @see getPersistenceMockHandler()
     */
    private $spbaseMockHandlers = array();

    /**
     * Returns a Base Handler mock.
     *
     * @param string $handler For instance "Content\\Type\\Handler" or "Search\\Handler", must be relative to "eZ\Publish\SPI"
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSPBaseMockHandler($handler, array $methods = [])
    {
        if (!isset($this->spbaseMockHandlers[$handler])) 
        {
            $this->spbaseMockHandlers[$handler] = $this->getMockBuilder("Eki\\NRW\\Component\\SPBase\\Persistence\\{$handler}")
                ->setMethods($methods)
                ->disableOriginalConstructor()
                ->setConstructorArgs(array())
                ->getMock();
        }

        return $this->spbaseMockHandlers[$handler];
    }

    /**
     * Returns a persistence Handler mock.
     *
     * @param string $handler For instance "Networking\\Handler", must be relative to "Eki\NRW\Component\Base\Persistence"
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPersistenceMockHandler($handler)
    {
        return $this->getSPBaseMockHandler("Persistence\\{$handler}");
    }

	/**
	* @var 
	*/
    private $persistenceComponentHandlerMocks = array();
    
    protected function getPersistenceComponentHandlerMock($handler, array $methods = array())
    {
		if (!isset($this->persistenceComponentHandlerMocks[$handler]) || !empty($methods))
		{
			$persistenceComponentHandlerMock = self::createMock("Eki\\NRW\\Component\\SPBase\\Persistence\\{$handler}");
			
			if (!empty($methods))
				return $persistenceComponentHandlerMock;
			
			$this->persistenceComponentHandlerMocks[$handler] = $persistenceComponentHandlerMock;
		}
		
		return $this->persistenceComponentHandlerMocks[$handler];
	}
    
    /**
	 * @var 
	 */
    protected $pluginsMock;
    
    /**
	* @var 
	*/
    protected $plugins;
    
    /**
	* Returns Plugins Mock
	* 
	* @return 
	*/
    protected function getPluginsMock(array $plugins = array())
    {
    	if ($this->pluginsMock === null || !empty($plugins))
    	{
			$pluginsMock = $this->getMockBuilder(PluginsInterface::class)
				->setMethods(['registerPlugin', 'getPlugin', 'hasPlugin'])
                ->getMock()
			;
			
		    $registeredPlugins = array();
			$pluginsMock->expects($this->any())
				->method('registerPlugin')
				->will($this->returnCallback(function ($plugin, $name) use (&$registeredPlugins) {
					$registeredPlugins[$name] = $plugin;
				}))
			;

			$pluginsMock->expects($this->any())
				->method('getPlugin')
				->will($this->returnCallback(function ($name) use (&$registeredPlugins) {
					return $registeredPlugins[$name];
				}))
			;

			$pluginsMock->expects($this->any())
				->method('hasPlugin')
				->will($this->returnCallback(function ($name) use (&$registeredPlugins) {
					return isset($registeredPlugins[$name]);
				}))
			;
			
			foreach($plugins as $name => $plugin)
			{
				$pluginsMock->registerPlugin($plugin, $name);
			}
			
			if (!empty($plugins))
				return $pluginsMock;
			
			$this->pluginsMock = $pluginsMock;
		}
		
		return $this->pluginsMock;
	}

    /**
	* Returns Plugins
	* 
	* @return 
	*/
    protected function getPlugins()
    {
    	if (!isset($this->plugins))
    	{
			$this->plugins = new Plugins();
		}
		
		return $this->plugins;
	}
}
