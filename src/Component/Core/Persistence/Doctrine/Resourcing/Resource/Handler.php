<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Resource;

use Eki\NRW\Component\SPBase\Persistence\Resourcing\Resource\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;

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
	* Create new resource object
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\ResourceInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	*/
	public function create($identifier)
	{
		try 
		{
			$resource = $this->factory->createNew($identifier);
		}
		catch(\InvalidArgumentException $e)
		{
			throw new InvalidArgumentException("identifier", "Resource identifier invalid.", $e);
		}
		
		$this->update($resource);

		return $resource;
	}
	
	/**
	* Load resource object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\ResourceInterface
	*/
	public function load($id)
	{
		if (null !== ($resource = $this->getObjectFromCache($id)))
			return $resource;

		$resource = $this->findRes($id);		
		if (null === $resource)
            throw new NotFoundException('Resource', $id);
		
		$this->setObjectToCache($resource);

		return $resource;
	}
	
	/**
	* Delete given resource
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\ResourceInterface $resource
	* 
	* @return void
	*/	
	public function delete(ResourceInterface $resource)
	{
		$this->clearObjectFromCache($resource);
		$this->objectManager->remove($resource);
	}
	
	/**
	* Update a resource identified by $id
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\ResourceeInterface $resource
	* 
	* @return void
	*/
	public function update(ResourceInterface $resource)
	{
		$this->setObjectToCache($resource);
		$this->objectManager->persist($resource);
	}
}
