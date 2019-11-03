<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Exchangeing\Exchange;

use Eki\NRW\Component\SPBase\Persistence\Exchangeing\Exchange\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Exchangeing\Exchange\ExchangeInterface;

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
			$exchange = $this->factory->createNew($identifier);
		}
		catch(\Exception $e)
		{
			throw new InvalidArgumentException($identifier, "Identifier '$identifier' invalid.");
		}
		
		$this->update($exchange);
		
		return $exchange;
	}

	/**
	* Load exchange object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Exchangeing\Exchange\ExchangeInterface
	*/
	public function load($id)
	{
		$cacheItem = $this->getCacheItem($id);	
		if ($cacheItem->isHit())
			return $cacheItem->get();

		$exchange = $this->findRes($id);		
		
		if (null === $exchange)
            throw new NotFoundException('Exchange', $id);
		
		$this->setCacheItem($exchange);

		return $exchange;
	}
	
	/**
	* Delete given exchange
	* 
	* @param \Eki\NRW\Component\Exchangeing\Exchange\ExchangeInterface $exchange
	* 
	* @return void
	*/	
	public function delete(ExchangeInterface $exchange)
	{
		$this->deleteCacheItem($exchange);
		$this->objectManager->remove($exchange);
	}
	
	/**
	* Update a exchange identified by $id
	* 
	* @param \Eki\NRW\Component\Exchangeing\Exchange\ExchangeeInterface $exchange
	* 
	* @return void
	*/
	public function update(ExchangeInterface $exchange)
	{
		$this->setCacheItem($exchange);
		$this->objectManager->persist($exchange);
	}
}
