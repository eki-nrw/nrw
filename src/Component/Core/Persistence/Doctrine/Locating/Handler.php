<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Locating;

use Eki\NRW\Component\SPBase\Persistence\Locating\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Locating\Location\LocationInterface;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Common\Res\Factory\FactoryInterface;
use Eki\NRW\Common\Res\Factory\Factory;
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
		$registries = [];
		foreach($metadata->getClasses() as $identifier => $className)
		{
			$registries[$identifier] = $className;
		}
		$this->factory = new Factory($registries);
		
		parent::__construct($objectManager, $cache, $metadata);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function create($identifier)
	{
		try 
		{
			$location = $this->factory->createNew($identifier);
		}
		catch(\InvalidArgumentException $e)
		{
			throw new InvalidArgumentException("identifier", "Location identifier invalid.", $e);
		}
		
		$this->update($location);

		return $location;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function load($id)
	{
		if (null !== ($location = $this->getObjectFromCache($id)))
			return $location;

		$location = $this->findRes($id);		
		if (null === $location)
            throw new NotFoundException('Location', $id);
		
		$this->setObjectToCache($location);

		return $location;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function delete(LocationInterface $location)
	{
		$this->clearObjectFromCache($location);
		$this->objectManager->remove($location);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function update(LocationInterface $location)
	{
		$this->setObjectToCache($location);
		$this->objectManager->persist($location);
	}
}
