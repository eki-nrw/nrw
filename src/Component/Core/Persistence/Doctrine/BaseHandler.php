<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine;

use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Eki\NRW\Component\Core\Cache\Res\Metadata\Cache as ResCache;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;

/**
 * Abstract implementation of Abstract Base Handler for Persistence 
 * A base handler manages one metadata of subject.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
abstract class BaseHandler extends AbstractHandler
{
	/**
	* @var MetadataInterface
	*/
	protected $metadata;
	
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
		MetadataInterface $metadata = null
	)
	{
		if ($metadata !== null)
		{
			try
			{
				$this->validateMetadata($metadata);
			}
			catch(\InvalidArgumentException $e)
			{
				throw new InvalidArgumentException("metadata", "Metadata invalid.", $e);
			}
		}
		
		$this->metadata = $metadata;
		$this->resCache = new ResCache($cache, $metadata);
		
		parent::__construct($objectManager, $cache);
	}
	
	/**
	* Validate metadata
	* 
	* @param MetadataInterface $metadata
	* 
	* @return void
	* 
	* @throw \InvalidArgumentException
	*/
	protected function validateMetadata(MetadataInterface $metadata)
	{
		if (!$metadata->hasParameter('cache_prefix'))	
			throw new \InvalidArgumentException("No cache prefix specified.");
		if (!$metadata->hasParameter('cache_tag'))	
			throw new \InvalidArgumentException("No cache tag specified.");
	}

	/**
	* Find resource by id
	* 
	* @param mixed $id
	* @param mixed $ref
	* 
	* @return object|null
	*/	
	protected function findRes($id, $ref = null)
	{
		$ref = $ref === null ? $this->_getDefaultRef() : $ref;
		if (!$this->metadata->hasClass($ref))
			throw new InvalidArgumentException("ref", "No resource $ref.");

		$res = $this->objectManager->find($this->metadata->getClass($ref), $id);
		
		return $res;
	}
	
	protected function _getDefaultRef()
	{
		return 'default';	
	}
	
	/**
	* Find all resources
	* 
	* @param mixed $ref
	* 
	* @return object[]|null
	*/
	protected function findAll($ref = null)
	{
		$ref = $ref === null ? $this->_getDefaultRef() : $ref;
		if (!$this->metadata->hasClass($ref))
			throw new InvalidArgumentException("$ref", "No resource $ref.");

		return $this->getRepository($ref)->findAll();
	}
	
	/**
	* Find resources by criteria
	* 
	* @param array $criteria
	* @param mixed $ref
	* @param array|null $orderBy
	* @param int|null $limit
	* @param int|null $offset
	* 
	* @return object[]|null
	*/
	protected function findResBy(array $criteria, $ref = null, array $orderBy = null, $limit = null, $offset = null)
	{
		$ref = $ref === null ? $this->_getDefaultRef() : $ref;
		if (!$this->metadata->hasClass($ref))
			throw new InvalidArgumentException("$ref", "No resource $ref.");

		return $this->getRepository($ref)->findBy($criteria, $orderBy, $limit, $offset);
	}

	/**
	* Find a resource by criteria
	* 
	* @param array $criteria
	* @param mixed $ref
	* 
	* @return object|null
	*/
	protected function findResOneBy(array $criteria, $ref = null)
	{
		$ref = $ref === null ? $this->_getDefaultRef() : $ref;
		if (!$this->metadata->hasClass($ref))
			throw new InvalidArgumentException("$ref", "No resource $ref.");

		return $this->getRepository($ref)->findOneBy($criteria);
	}
	
	/**
	* Get repository of 'reference' class
	* 
	* @param string $ref
	* 
	* @return string
	*/
	protected function getRepository($ref)
	{
		return $this->objectManager->getRepository($this->metadata->getClass($ref));
	}
}
