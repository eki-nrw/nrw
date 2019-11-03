<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Eventing\Event;

use Eki\NRW\Component\SPBase\Persistence\Eventing\Event\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Processing\Event\EventInterface;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;

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
		MetadataInterface $metadata,
		FactoryInterface $factory = null
	)
	{
		parent::__construct($objectManager, $cache, $metadata);

		if ($factory === null)
		{
			$factoryRegistries = [];
			foreach($metadata->getClasses() as $ref => $class)
			{
				$factoryRegistries[$ref] = $class; 	
			}
			
			$factory = new Factory($factoryRegistries);
		}	
			
		$this->factory = $factory;
	}

	/**
	* @inheritdoc
	*/
	public function create($identifier)
	{
		if (!$this->factory->support($identifier))
			throw new InvalidArgumentException("identifier", "Identifier '$identifier' invalid.");

		try 
		{
			$event = $this->factory->createNew($identifier);
		}
		catch(\Exception $e)
		{
			throw new InvalidArgumentException($identifier, "Identifier '$identifier' invalid.");
		}
		
		$this->update($event);
		
		return $event;
	}

	/**
	* Load event object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Eventing\Event\EventInterface
	*/
	public function load($id)
	{
		$cacheItem = $this->getCacheItem($id);	
		if ($cacheItem->isHit())
			return $cacheItem->get();

		$event = $this->findRes($id);		
		
		if (null === $event)
            throw new NotFoundException('Event', $id);
		
		$this->setCacheItem($event);

		return $event;
	}
	
	/**
	* Delete given event
	* 
	* @param \Eki\NRW\Component\Eventing\Event\EventInterface $event
	* 
	* @return void
	*/	
	public function delete(EventInterface $event)
	{
		$this->deleteCacheItem($event);
		$this->objectManager->remove($event);
	}
	
	/**
	* Update a event identified by $id
	* 
	* @param \Eki\NRW\Component\Eventing\Event\EventeInterface $event
	* 
	* @return void
	*/
	public function update(EventInterface $event)
	{
		$this->setCacheItem($event);
		$this->objectManager->persist($event);
	}
}
