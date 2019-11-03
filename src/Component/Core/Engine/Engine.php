<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine;

use Eki\NRW\Component\Base\Engine\Engine as EngineInterface;
use Eki\NRW\Component\Base\Persistence\Handler as PersistenceHandler;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentValue;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentType;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Eki\NRW\Component\Core\Engine\Permission\CachedPermissionService;
use Eki\NRW\Component\Core\Engine\Permission\PermissionCriterionResolver;
use Eki\NRW\Component\Notification\NotificatorInterface;

use Eki\NRW\Component\Core\Engine\RoleService;
use Eki\NRW\Component\Core\Engine\NetworkingService;
use Eki\NRW\Component\Core\Engine\ResourcingService;
use Eki\NRW\Component\Core\Engine\RelatingService;
use Eki\NRW\Component\Core\Engine\WorkingService;
use Eki\NRW\Component\Core\Engine\SearchService;

use Exception;
use RuntimeException;

/**
 * Engine implementation.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class Engine implements EngineInterface
{
	/**
	* @var string
	*/
	protected $engineName;

    /**
     * Service settings, first level key is service name.
     *
     * @var array
     */
    protected $serviceSettings;
    
	/**
	* @var PersistenceHandler
	*/
	protected $persistenceHandler;

	/**
	* @var \Eki\NRW\Component\Base\Engine\RoleService
	*/
	protected $roleService;

	/**
	* @var \Eki\NRW\Component\Base\Engine\NetworkingService
	*/
	protected $networkingService;
	
	/**
	* @var \Eki\NRW\Component\Base\Engine\ResourcingService
	*/
	protected $resourcingService;

	/**
	* @var \Eki\NRW\Component\Base\Engine\WorkingService
	*/
	protected $workingService;

	/**
	* @var \Eki\NRW\Component\Base\Engine\RelatingService
	*/
	protected $relatingService;

	/**
	* @var \Eki\NRW\Component\Base\Engine\SearchService
	*/
	protected $searchService;

	/**
	* @var \Eki\NRW\Component\Core\Engine\permission\LimitationService
	*/
	protected $limitationService;
	
	/**
	* @var \Eki\NRW\Component\Core\Permission\RoleDomainMapper
	*/
	protected $roleDomainMapper;
	
    /**
     * Instance of permissions-resolver and -criterion resolver.
     *
     * @var \Eki\NRW\Component\Base\Engine\PermissionCriterionResolver|\Eki\NRW\Component\Base\Engine\PermissionResolver
     */
    protected $permissionsHandler;
    
    /**
	* @var Plugins
	*/
    protected $plugins;

	/**
     * @var \Eki\NRW\Component\Core\Search\Common\BackgroundIndexer|null
     */
    protected $backgroundIndexer;
    
    public function __construct(
    	$engineName,
    	array $serviceSettings,
		PersistenceHandler $persistenceHandler,
		Plugins $plugins
    )
    {
		$this->engineName = $engineName;
		$this->persistenceHandler = $persistenceHandler;
		$this->plugins = $plugins;

		$this->serviceSettings = $serviceSettings + array(
			'networking' => array(),
			'resourcing' => array(),
			'processing' => array(),
			'working' => array(),

			'relating' => array(),
			'locating' => array(),
			
			'search' => array(),
			
			'role' => array(
				'policyMap' => array(),
				'limitationTypes' => array(),
			),
			'user' => array(
				'anonymousUserID' => 10,  // ????
			)
		);
	}
    
	/**
	* Returns the name of engine
	* 
	* @return string
	*/
	public function getEngineName()
	{
		return $this->engineName;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getService($serviceName)
	{
		$method = 'get'.ucfirst($serviceName).'Service');
		if (method_exists($this, $method))
		{
			return $this->$method();
		}
	}

	/**
	* Return tool helper
	* 
	* @param string $name
	* 
	* @return object
	*/
	public function getSystemTool($name)
	{
		return $this->plugins->getPlugin($name);
	}

	/**
	* Returns Role Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\RoleService
	*/	
	public function getRoleService()
	{
		if ($this->networkingService === null)
		{
			$this->roleService = new RoleService(
				$this,
				$this->serviceSettings['role'],
				$this->persistenceHandler->permissionHandler()
			);
		}
	}

	/**
	* Returns Networking Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\NetworkingService
	*/	
	public function getNetworkingService()
	{
		if ($this->networkingService === null)
		{
			$this->networkingService = new NetworkingService(
				$this,
				$this->serviceSettings['networking'],
				$this->persistenceHandler->networkingHandler(),
				$this->plugins->getPlugin('agentTypeBuilder'),
				$this->plugins->getPlugin('agentBuilder')
			);
		}
		
		return $this->networkingService;
	}

	/**
	* Returns Resourcing Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\ResourcingService
	*/	
	public function getResourcingService()
	{
		if ($this->resourcingService === null)
		{
			$this->resourcingService = new ResourcingService(
				$this,
				$this->serviceSettings['resourcing'],
				$this->persistenceHandler->resourcingHandler()
			);
		}
		
		return $this->resourcingService;
	}

	/**
	* Returns Processing Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\ProcessingService
	*/	
	//public function getProcessingService()
	//{
		
	//}
	
	public function getWorkingService()
	{
		if ($this->workingService === null)
		{
			$this->workingService = new WorkingService(
				$this,
				$this->serviceSettings['working'],
				$this->persistenceHandler->workingHandler()
			);
		}

		return $this->workingService;
	}
	
	//public function getExchangingService();
	
	//public function getClaimingService();
	
	/**
	* Returns Relating Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\RelatingService
	*/	
	public function getRelatingService()
	{
		if ($this->relatingService === null)
		{
			$this->relatingService = new RelatingService(
				$this,
				$this->serviceSettings['relating'],
				$this->persistenceHandler
			);
		}
		
		return $this->relatingService;
	}
	
	// user/identity service
	
	// role service
	
	// search service
	
    /**
     * Get SearchService.
     *
     * @return \Eki\NRW\Component\Core\Engine\SearchService
     */
    /*
    public function getSearchService()
    {
        if ($this->searchService !== null) 
        {
            return $this->searchService;
        }

        $this->searchService = new SearchService(
            $this,
            $this->searchHandler,
            $this->getDomainMapper(),
            $this->getPermissionCriterionResolver(),
            $this->backgroundIndexer,
            $this->serviceSettings['search']
        );

        return $this->searchService;
    }
    */
	
	// translation service
	
	// trash service
	
	// history service
	
	// vas service
	
	// accounting service
	
	// distribution service
	
	// app service
	
	// tagging service
	
	// faceting service

    /**
     * Get LimitationService.
     *
     * @return \Eki\NRW\Component\Core\engine\Domain\LimitationService
     */
/*
    protected function getLimitationService()
    {
        if ($this->limitationService !== null) {
            return $this->limitationService;
        }

        $this->limitationService = new Permission\LimitationService($this->serviceSettings['role']);

        return $this->limitationService;
    }
*/

	/**
     * Get RoleDomainMapper.
     *
     * @return \Eki\NRW\Component\Core\Engine\Permission\RoleDomainMapper
     */
/*
    protected function getRoleDomainMapper()
    {
        if ($this->roleDomainMapper !== null) 
        {
            return $this->roleDomainMapper;
        }

        $this->roleDomainMapper = new Permission\RoleDomainMapper($this->getLimitationService());

        return $this->roleDomainMapper;
    }
*/
  
    /**
     * Get PermissionResolver.
     *
     * @return \Eki\NRW\Component\Base\Engine\PermissionResolver
     */
    public function getPermissionResolver()
    {
        return $this->getCachedPermissionsResolver();
    }

    /**
     * @return \Eki\NRW\Component\Base\Engine\Permission\PermissionCriterionResolver|\Eki\NRW\Component\Base\Engine\Permission\PermissionResolver
     */
    protected function getCachedPermissionsResolver()
    {
        if ($this->permissionsHandler === null) 
        {
            $this->permissionsHandler = new CachedPermissionService(
                $permissionResolver = new Permission\PermissionResolver(
                    $this->getRoleDomainMapper(),
                    $this->getLimitationService(),
                    $this->persistenceHandler->userHandler(),
                    $this->serviceSettings['role']['policyMap']
                ),
                new PermissionCriterionResolver(
                    $permissionResolver,
                    $this->getLimitationService()
                )
            );
        }

        return $this->permissionsHandler;
    }

    /**
     * Allows API execution to be performed with full access sand-boxed.
     *
     * The closure sandbox will do a catch all on exceptions and rethrow after
     * re-setting the sudo flag.
     *
     * Example use:
     *     $agent = $engine->sudo(
     *         function ( Engine $eng ) use ( $agentId )
     *         {
     *             return $eng->networkingService()->loadAgent( $agentId )
     *         }
     *     );
     *
     *
     * @param \Closure $callback
     * @param \Eki\NRW\Component\Base\Engine\Engine|null $outerEngine
     *
     * @throws \RuntimeException Thrown on recursive sudo() use.
     * @throws \Exception Re throws exceptions thrown inside $callback
     *
     * @return mixed
     */
    public function sudo(Closure $callback, EngineInterface $outerEngine = null)
    {
        return $this->getPermissionResolver()->sudo($callback, $outerEngine ?: $this);
    }
    
     /**
     * Begin transaction.
     *
     * Begins an transaction, make sure you'll call commit or rollback when done,
     * otherwise work will be lost.
     */
    public function beginTransaction()
    {
		$this->persistenceHandler->beginTransaction();
	}

    /**
     * Commit transaction.
     *
     * Commit transaction, or throw exceptions if no transactions has been started.
     *
     * @throws \RuntimeException If no transaction has been started
     */
    public function commit()
    {
    	try 
    	{
			$this->persistenceHandler->commit();
        } 
        catch (Exception $e) 
        {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
	}

    /**
     * Rollback transaction.
     *
     * Rollback transaction, or throw exceptions if no transactions has been started.
     *
     * @throws \RuntimeException If no transaction has been started
     */
    public function rollback()
    {
    	try 
    	{
			$this->persistenceHandler->rollBack();
        } 
        catch (Exception $e) 
        {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
	}
}
