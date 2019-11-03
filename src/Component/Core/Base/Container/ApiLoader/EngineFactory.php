<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 
namespace Eki\NRW\Component\Core\Base\Container\ApiLoader;

use Eki\NRW\Component\Base\Enine\Engine;
use Eki\NRW\Component\Persistence\Handler as PersistenceHandler;
use Eki\NRW\Component\Base\Permission\Role\Limitation\Type as LimitationType;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Engine factory
 */
class EngineFactory extends ContainerAwareInterface
{
    use ContainerAwareTrait;

	/**
	* @var string
	*/
	private $engineClass;
	
    /**
     * Collection of limitation types for the RoleService.
     *
     * @var \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation[]
     */
    protected $roleLimitations = array();

    /**
     * Policies map.
     *
     * @var array
     */
    protected $policyMap = array();
	
	public function __constructor(
		$engineClass,
		array $policyMap
	)
	{
		$this->engineClass = $engineClass;
		$this->policyMap = $policyMap;
	}
	
	/**
	* Build the main engine
	* 
	* @param string $engineName
	* @param array $serviceSettings
	* @param \Eki\NRW\Component\Persistence\Handler $persistenceHandler
	* 
	* @return
	*/
	public function buildEngine(
    	$engineName,
    	array $serviceSettings,
		PersistenceHandler $persistenceHandler	
	)
	{
		$engine = new $this->engineClass(
			$engineName,
			array(
                'role' => array(
                    'limitationTypes' => $this->roleLimitations,
                    'policyMap' => $this->policyMap,
                ),
                'languages' => $this->container->getParameter('languages'),
			),
			$persistenceHandler,
			
		);
		
		return $engine;		
	}
	
    /**
     * Returns a service based on a name string (networking => networkingService, etc).
     *
     * @param \Eki\NRW\Component\Base\Engine\Engine $engine
     * @param string $serviceName
     *
     * @throws \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
     *
     * @return mixed
     */
    public function buildService(Engine $engine, $serviceName)
    {
        $methodName = 'get' . $serviceName . 'Service';
        if (!method_exists($engine, $methodName)) {
            throw new InvalidArgumentException($serviceName, 'No such service');
        }

        return $engine->$methodName();
    }
}
