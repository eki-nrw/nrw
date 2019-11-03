<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Networking\Agent\Type;

use Eki\NRW\Component\SPBase\Persistence\Networking\Agent\Type\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface;

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
	* @var FactoryInterface
	*/
	protected $factory;

	/**
	* Constructor
	* 
	* @param ObjectManager $objectManager
	* @param Cache $cache
	* @param MetadataInterface $metadata
	* @param FactoryInterface|null $factory
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
		
		$factory = new Factory($factoryRegistries);
			
		$this->factory = $factory;
	}
	
	/**
	* Create new agent type object
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface
	*/
	public function create($identifier)
	{
		if (null !== $this->loadByIdentifier($identifier))
			throw new InvalidArgumentException("identifier", "Agent Type identifier $identifier already exists.", $e);
		
		try 
		{
			$agentType = $this->factory->createNew($identifier);
		}
		catch(\InvalidArgumentException $e)
		{
			throw new InvalidArgumentException("identifier", "Agent Type identifier invalid.", $e);
		}
		
		$this->update($agentType);

		return $agentType;
	}

	/**
	* Load agent object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface
	*/
	public function load($id)
	{
		if (null !== ($agentType = $this->getObjectFromCache($id)))
			return $agentType;

		$agentType = $this->findRes($id);		
		if (null === $agentType)
            throw new NotFoundException('AgentType', $id);
		
		$this->setObjectToCache($agentType);		

		return $agentType;
	}

	/**
	* @inheritdoc
	*/
	public function loadByIdentifier($identifier)
	{
		if (null !== ($agentType = $this->getObjectFromCache($identifier, 'identifier')))
			return $agentType;

		if (null === ($agentType = $this->findResOneBy(array('identifier' => $identifier))))
			return null;
		
		$this->setObjectToCache($agentType, 'identifier');		

		return $agentType;
	}
	
	/**
	* @inheritdoc
	*/
	public function delete(AgentTypeInterface $agentType)
	{
		$this->clearObjectFromCache($agentType);
		$this->clearObjectFromCache($agentType, 'identifier');
		$this->objectManager->remove($agentType);
	}
	
	/**
	* Update a agent type identified by $id
	* 
	* @param \Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface $agentType
	* 
	* @return void
	*/
	public function update(AgentTypeInterface $agentType)
	{
		$this->setObjectToCache($agentType);
		$this->setObjectToCache($agentType, 'identifier');
		$this->objectManager->persist($agentType);
	}
}
