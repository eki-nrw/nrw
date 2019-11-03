<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Resource\Type;

use Eki\NRW\Component\SPBase\Persistence\Resourcing\Resource\Type\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface;

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
	* Create new resource type object
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface
	*/
	public function create($identifier)
	{
		if (null !== $this->loadByIdentifier($identifier))
			throw new InvalidArgumentException("identifier", "Resource Type identifier $identifier already exists.", $e);

		try 
		{
			$resourceType = $this->factory->createNew($identifier);
		}
		catch(\InvalidArgumentException $e)
		{
			throw new InvalidArgumentException("identifier", "Resource Type identifier invalid.", $e);
		}
		
		$this->update($resourceType);

		return $resourceType;
	}

	/**
	* Load resource object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface
	*/
	public function load($id)
	{
		if (null !== ($resourceType = $this->getObjectFromCache($id)))
			return $resourceType;

		$resourceType = $this->findRes($id);		
		if (null === $resourceType)
            throw new NotFoundException('ResourceType', $id);
		
		$this->setObjectToCache($resourceType);		

		return $resourceType;
	}

	/**
	* @inheritdoc
	*/
	public function loadByIdentifier($identifier)
	{
		if (null !== ($resourceType = $this->getObjectFromCache($identifier, 'identifier')))
			return $resourceType;

		if (null === ($resourceType = $this->findResOneBy(array('identifier' => $identifier))))
			return null;
		
		$this->setObjectToCache($resourceType, 'identifier');		

		return $resourceType;
	}
	
	/**
	* @inheritdoc
	*/
	public function delete(ResourceTypeInterface $resourceType)
	{
		$this->clearObjectFromCache($resourceType);
		$this->clearObjectFromCache($resourceType, 'identifier');
		$this->objectManager->remove($resourceType);
	}
	
	/**
	* Update a resource type identified by $id
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface $resourceType
	* 
	* @return void
	*/
	public function update(ResourceTypeInterface $resourceType)
	{
		$this->setObjectToCache($resourceType);
		$this->setObjectToCache($resourceType, 'identifier');
		$this->objectManager->persist($resourceType);
	}
}
