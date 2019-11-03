<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Processing\Frame;

use Eki\NRW\Component\SPBase\Persistence\Processing\Frame\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Processing\Frame\FrameInterface;

use Eki\NRW\Common\Res\Factory\FactoryInterface;

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
			$frame = $this->factory->createNew($identifier);
		}
		catch(\Exception $e)
		{
			throw new InvalidArgumentException($identifier, "Identifier '$identifier' invalid.");
		}
		
		$this->update($frame);
		
		return $frame;
	}

	/**
	* Load frame object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Processing\Frame\FrameInterface
	*/
	public function load($id)
	{
		$cacheItem = $this->getCacheItem($id);	
		if ($cacheItem->isHit())
			return $cacheItem->get();

		$frame = $this->findRes($id);		
		
		if (null === $frame)
            throw new NotFoundException('Frame', $id);
		
		$this->setCacheItem($frame);

		return $frame;
	}
	
	/**
	* Delete given frame
	* 
	* @param \Eki\NRW\Component\Processing\Frame\FrameInterface $frame
	* 
	* @return void
	*/	
	public function delete(FrameInterface $frame)
	{
		$this->deleteCacheItem($frame);
		$this->objectManager->remove($frame);
	}
	
	/**
	* Update a frame identified by $id
	* 
	* @param \Eki\NRW\Component\Processing\Frame\FrameeInterface $frame
	* 
	* @return void
	*/
	public function update(FrameInterface $frame)
	{
		$this->setCacheItem($frame);
		$this->objectManager->persist($frame);
	}
}
