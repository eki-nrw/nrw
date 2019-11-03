<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Processing\Process;

use Eki\NRW\Component\SPBase\Persistence\Processing\Process\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Processing\Process\ProcessInterface;

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
			$process = $this->factory->createNew($identifier);
		}
		catch(\Exception $e)
		{
			throw new InvalidArgumentException($identifier, "Identifier '$identifier' invalid.");
		}
		
		$this->update($process);
		
		return $process;
	}

	/**
	* Load process object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Processing\Process\ProcessInterface
	*/
	public function load($id)
	{
		$cacheItem = $this->getCacheItem($id);	
		if ($cacheItem->isHit())
			return $cacheItem->get();

		$process = $this->findRes($id);		
		
		if (null === $process)
            throw new NotFoundException('Process', $id);
		
		$this->setCacheItem($process);

		return $process;
	}
	
	/**
	* Delete given process
	* 
	* @param \Eki\NRW\Component\Processing\Process\ProcessInterface $process
	* 
	* @return void
	*/	
	public function delete(ProcessInterface $process)
	{
		$this->deleteCacheItem($process);
		$this->objectManager->remove($process);
	}
	
	/**
	* Update a process identified by $id
	* 
	* @param \Eki\NRW\Component\Processing\Process\ProcesseInterface $process
	* 
	* @return void
	*/
	public function update(ProcessInterface $process)
	{
		$this->setCacheItem($process);
		$this->objectManager->persist($process);
	}
}
