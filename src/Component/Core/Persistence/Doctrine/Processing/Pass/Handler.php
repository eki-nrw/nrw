<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Passing\Pass;

use Eki\NRW\Component\SPBase\Persistence\Passing\Pass\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Processing\Pass\PassInterface;

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
			$pass = $this->factory->createNew($identifier);
		}
		catch(\Exception $e)
		{
			throw new InvalidArgumentException($identifier, "Identifier '$identifier' invalid.");
		}
		
		$this->update($pass);
		
		return $pass;
	}

	/**
	* Load pass object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Passing\Pass\PassInterface
	*/
	public function load($id)
	{
		$cacheItem = $this->getCacheItem($id);	
		if ($cacheItem->isHit())
			return $cacheItem->get();

		$pass = $this->findRes($id);		
		
		if (null === $pass)
            throw new NotFoundException('Pass', $id);
		
		$this->setCacheItem($pass);

		return $pass;
	}
	
	/**
	* Delete given pass
	* 
	* @param \Eki\NRW\Component\Passing\Pass\PassInterface $pass
	* 
	* @return void
	*/	
	public function delete(PassInterface $pass)
	{
		$this->deleteCacheItem($pass);
		$this->objectManager->remove($pass);
	}
	
	/**
	* Update a pass identified by $id
	* 
	* @param \Eki\NRW\Component\Passing\Pass\PasseInterface $pass
	* 
	* @return void
	*/
	public function update(PassInterface $pass)
	{
		$this->setCacheItem($pass);
		$this->objectManager->persist($pass);
	}
}
