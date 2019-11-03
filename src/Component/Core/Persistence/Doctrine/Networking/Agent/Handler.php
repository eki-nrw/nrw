<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Agent;

use Eki\NRW\Component\SPBase\Persistence\Networking\Agent\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Networking\Agent\AgentInterface;

use Eki\NRW\Common\Res\Factory\FactoryInterface;
use Eki\NRW\Common\Res\Factory\Factory;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Handler extends BaseHandler implements HandlerInterface
{
	/**
	* @var Factory
	*/
	protected $factory;

	/**
	* Constructor
	* 
	* @param ObjectManager $objectManager
	* @param Cache $cache
	* @param MetadataInterface $metadata
	* 
	*/
	public function __construct(
		ObjectManager $objectManager,
		Cache $cache,
		MetadataInterface $metadata
	)
	{
		parent::__construct($objectManager, $cache, $metadata);

		$factoryRegistries = [];
		foreach($metadata->getClasses() as $identifier => $class)
		{
			$factoryRegistries[$identifier] = $class; 	
		}
		
		$this->factory = new Factory($factoryRegistries);
	}
	
	/**
	* Create new agent object
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Networking\Agent\AgentInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	*/
	public function create($identifier)
	{
		try 
		{
			$agent = $this->factory->createNew($identifier);
		}
		catch(\InvalidArgumentException $e)
		{
			throw new InvalidArgumentException("identifier", "Agent identifier invalid.", $e);
		}
		
		$this->update($agent);

		return $agent;
	}
	
	/**
	* Load agent object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Networking\Agent\AgentInterface
	*/
	public function load($id)
	{
		if (null !== ($agent = $this->getObjectFromCache($id)))
			return $agent;

		$agent = $this->findRes($id);		
		if (null === $agent)
            throw new NotFoundException('Agent', $id);

		$this->setObjectToCache($agent);		

		return $agent;
	}
	
	/**
	* Delete given agent
	* 
	* @param \Eki\NRW\Component\Networking\Agent\AgentInterface $agent
	* 
	* @return void
	*/	
	public function delete(AgentInterface $agent)
	{
		$this->clearObjectFromCache($agent);
		$this->objectManager->remove($agent);
	}
	
	/**
	* Update a agent identified by $id
	* 
	* @param \Eki\NRW\Component\Networking\Agent\AgenteInterface $agent
	* 
	* @return void
	*/
	public function update(AgentInterface $agent)
	{
		$this->setObjectToCache($agent);
		$this->objectManager->persist($agent);
	}
}
