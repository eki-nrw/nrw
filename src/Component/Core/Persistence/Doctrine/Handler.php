<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine;

use Eki\NRW\Component\SPBase\Persistence\Handler as HandlerInterface;

use Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Handler as NetworkingHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Handler as ResourcingHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Relating\Handler as RelatingHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Working\Handler as WorkingHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Permission\Handler as PermissionHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\TransactionHandler;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;

use Eki\NRW\Common\Res\Metadata\RegistryInterface;

use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;
use Doctrine\Common\Persistence\ObjectManager;

/**
* Persistence Handler implmentation 
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Handler extends GroupHandler implements HandlerInterface
{
	/**
	* @var \Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Handler
	*/
	protected $networkingHandler;
	
	/**
	* @var \Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Handler
	*/
	protected $resourcingHandler;

	/**
	* @var \Eki\NRW\Component\Core\Persistence\Doctrine\Working\Handler
	*/
	protected $workingHandler;

	/**
	* @var \Eki\NRW\Component\Core\Persistence\Doctrine\Relating\Handler
	*/	
	protected $relatingHandler;
	
	/**
	* @var \Eki\NRW\Component\Core\Persistence\Doctrine\TransactionHandler
	*/
	protected $transactionHandler;

	/**
	* Constructor
	* 
	* @param ObjectManager $objectManager
	* @param Cache $cache
	* @param RegistryInterface $registry Metadata registry
	* @param FactoryInterface $factory
	* 
	* @return
	*/
	public function __construct(
		ObjectManager $objectManager,
		Cache $cache,
		array $metadatas
	)
	{
		parent::__construct($objectManager, $cache, $metadatas);
	}
	
	public function loadObject($id, array $params)
	{
		if (isset($params['class']))
		{
			return $this->objectManager->find($params['class'], $id);
		}
		
		throw new NotFoundException("object", 
			array(
				'id' => $id,
				'params' => $params
			)
		);
	}

	/**
	* Returns persistence handler of networking
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Networking\Handler
	*/
	public function networkingHandler()
	{
		if ($this->networkingHandler === null)
		{
			$this->networkingHandler = new NetworkingHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->extractRegistry(array('agent_type', 'agent'))
			);
		}
		
		return $this->networkingHandler;
	}
	
	/**
	* Returns persistence handler of resourcing
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Resourcing\Handler
	*/
	public function resourcingHandler()
	{
		if ($this->resourcingHandler === null)
		{
			$this->resourcingHandler = new ResourcingHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->extractRegistry(array('resource_type', 'resource'))
			);
		}
		
		return $this->resourcingHandler;
	}

	/**
	* Returns persistence handler of working
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Working\Handler
	*/
	public function workingHandler()
	{
		if ($this->workingHandler === null)
		{
			$this->workingHandler = new WorkingHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->extractRegistry(array('plan', 'plan_item', 'activity', 'execution'))
			);
		}
		
		return $this->workingHandler;
	}

	/**
	* @inheritdoc
	* 
	*/	
	public function relatingHandler()
	{
		if ($this->relatingHandler === null)
		{
			$this->relatingHandler = new RelatingHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('relating')
			);
		}
		
		return $this->relatingHandler;
	}
	
	public function transactionHandler()
	{
		if ($this->transactionHandler !== null)
		{
			$this->transactionHandler = new TransactionHandler(
				$this->objectManager
			);
		}
		
		return $this->transactionHandler;	
	}

	/**
	* Returns persistence handler of permission
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Handler
	*/
	public function permissionHandler()
	{
		if ($this->permissionHandler === null)
		{
			$this->permissionHandler = new PermissionHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->extractRegistry(array('user', 'role'))
			);
		}
		
		return $this->permissionHandler;
	}
}
