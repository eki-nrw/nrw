<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Relating;

use Eki\NRW\Component\SPBase\Persistence\Relating\Handler as HandlerInterface;
use Eki\NRW\Component\SPBase\Persistence\Relating\Relation as RelationInterface;
use Eki\NRW\Component\SPBase\Persistence\Relating\Repository as RelationRepository;

use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Relating\Relation;

use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Factory\Factory;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

use ReflectionClass;

/**
 * Relating Handler implementation
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class Handler extends BaseHandler implements HandlerInterface
{
	/**
	* @var Factory
	*/
	protected $factory;
	
	/**
	* Constructor
	* 
	* @param ObjectManager $objectManager
	* @param Cache $cache
	* @param MetadataInterface|null $metadata
	* @param string $persistenceObjectClass
	* 
	*/
	public function __construct(
		ObjectManager $objectManager,
		Cache $cache,
		MetadataInterface $metadata
	)
	{
		parent::__construct($objectManager, $cache, $metadata);
		
		$registries = [];
		foreach($metadata->getClasses() as $identifier => $className)
		{
			$registries[$identifier] = $className;
		}
		
		$this->factory = new Factory($registries);
	}
	
	/**
	* @inheritdoc
	*/
	public function createRelation($identifier, $type)
	{
		try 
		{
			$relation = $this->factory->createNew($identifier);
			$relation->identifier = $identifier;
			$relation->type = $type;
		}
		catch(\InvalidArgumentException $e)
		{
			throw new InvalidArgumentException("identifier", "Relation identifier invalid.", $e);
		}
				
		$this->updateRelation($relation);
		
		return $relation;
	}
	
	/**
	* @inheritdoc
	*/
	public function loadRelation($relationId)
	{
		if (null !== ($relation = $this->getObjectFromCache($relationId)))
			return $relation;

		$relation = $this->findRes($relationId);		
		if (null === $relation)
            throw new NotFoundException('Relation', $relationId);
		
		$this->setObjectToCache($relation);		

		return $relation;
	}

	/**
	* @inheritdoc
	*/
	public function deleteRelation(RelationInterface $relation)
	{
		$this->clearObjectFromCache($relation);
		$this->objectManager->remove($relation);
	}
	
	/**
	* @inheritdoc
	*/
	public function updateRelation(RelationInterface $relation)
	{
		$this->setObjectToCache($relation);
		$this->objectManager->persist($relation);
	}
	
	/**
	* @inheritdoc
	*/
	public function getRelations(array $objectInfo, $identifier = null, $type = null, $offset = 0, $limit = 25)
	{
		if (!isset($objectInfo['id']) or !isset($objectInfo['class']) or !isset($objectInfo['base']))
			throw new InvalidArgumentException("objectInfo", "Parameter 'objectInfo' must be array that has indices ['id', 'class', 'base']");
			
		$handler = $this;
		$func = function ($repository, $iden) use ($handler, $objectInfo, $type, $offset, $limit) {
			if ($repository instanceof RelationRepository)
				$relations = $repository->findAllRelations($objectInfo, $iden, $type, $offset, $limit);
			else
				$relations = $handler->getRelationsFromRepo(
					$repository, 
					$objectInfo, 
					$iden, $type, 
					$offset, $limit
				);
			
			return $relations;
		};

		return $this->__getRelations($identifier, $func);
	}

	/**
	* @inheritdoc
	*/
	public function getRelationsBetween(array $baseObjectInfo, array $otherObjectInfo, $identifier = null, $type = null)
	{
		if (!isset($baseObjectInfo['id']) or !isset($baseObjectInfo['class']))
			throw new InvalidArgumentException("baseObjectInfo", "Parameter 'baseObjectInfo' must be array of indices ['id', 'class']");
		if (!isset($otherObjectInfo['id']) or !isset($otherObjectInfo['class']))
			throw new InvalidArgumentException("otherObjectInfo", "Parameter 'otherObjectInfo' must be array of indices ['id', 'class']");

		$handler = $this;
		$func = function ($repository, $iden) use ($handler, $baseObjectInfo, $otherObjectInfo, $type) {
			if ($repository instanceof RelationRepository)
				$relations = $repository->getRelationsBetween($baseObjectInfo, $otherObjectInfo, $iden, $type);
			else
				$relations = $handler->getRelationsBetweenFromRepo(
					$repository, 
					$baseObjectInfo, $otherObjectInfo, 
					$iden, $type
				);
			
			return $relations;
		};

		return $this->__getRelations($identifier, $func);
	}

	/**
	* @inheritdoc
	*/
	private function __getRelations($identifier, callable $func)
	{
		if ($identifier === null)
			$classes = $this->metadata->getClasses();
		else
			$classes = array( $identifier => $this->metadata->getClass($identifier) );
		
		$relations = [];
		foreach($classes as $iden => $class)
		{
			$repository = $this->getRepository($iden);
			$relations = array_merge($relations, $func($repository, $iden));
		}
			
		return $relations;
	}

	private function getRelationsFromRepo(
		ObjectRepository $repository, 
		array $objectInfo, 
		$identifier, $type, 
		$offset, $limit
	)
	{
		$criteria = [];
		if (!empty($identifier))
			$criteria['identifier'] = $identifier;
		if (!empty($type))
			$criteria['type'] = $type;

		$orderBy = array(
			'identifier' => 'ASC',
			'type' => 'ASC'
		);
		
		try 
		{
			$foundRelations = $repository->findBy($criteria, $orderBy, $limit, $offset);
			
			$relations = $this->findRelationsMatchedObject(
				$foundRelations, 
				$objectInfo['id'], $objectInfo['class'], $objectInfo['base']
			);
		}
		catch(\UnexpectedValueException $e)
		{
			$relations = [];	
		}
		
		return $relations;
	}

	private function getRelationsBetweenFromRepo(
		ObjectRepository $repository, 
		array $baseObjectInfo, array $otherObjectInfo, 
		$identifier, $type
	)
	{
		$criteria = [];
		if ($identifier !== null)
			$criteria['identifier'] = $identifier;
		if ($type !== null)
			$criteria['type'] = $type;

		$orderBy = array(
			'identifier' => 'ASC',
			'type' => 'ASC'
		);
		
		try 
		{
			$foundRelations = $repository->findBy($criteria, $orderBy);
			$relations = $this->findRelationsMatchedBetween($foundRelations, $baseObjectInfo, $otherObjectInfo);
		}
		catch(\UnexpectedValueException $e)
		{
			$relations = [];	
		}
		
		return $relations;
	}
	
	/**
	* 
	* @param Relation[] $foundRelations
	* @param mixed $objectId
	* @param string $objectClass
	* @param bool $isBase
	* 
	* @return
	*/
	private function findRelationsMatchedObject(array $foundRelations, $objectId, $objectClass, $isBase)
	{
		$relations = [];
		if ($isBase)
		{
			foreach($foundRelations as $foundRelation)
			{
				$base = $foundRelation->getBase();
				if ($base['id'] === $objectId and $base['class'] === $objectClass)
				{
					$relations[] = $foundRelation;
				}
			}
		}
		else
		{
			foreach($foundRelations as $foundRelation)
			{
				$others = $foundRelation->getOthers();
				foreach($others as $key => $other)
				{
					if ($other['id'] === $objectId and $other['class'] === $objectClass)
					{
						$relations[] = $foundRelation;
					}
				}
			}
		}
		
		return $relations;
	}

	private function findRelationsMatchedBetween(array $foundRelations, array $baseObjectInfo, array $otherObjectInfo)
	{
		$relations = [];
		foreach($foundRelations as $foundRelation)
		{
			$base = $foundRelation->getBase();
			if ($base['id'] === $baseObjectInfo['id'] and $base['class'] === $baseObjectInfo['class'])
			{
				$others = $foundRelation->getOthers();
				foreach($others as $key => $other)
				{
					if ($other['id'] === $otherObjectInfo['id'] and $other['class'] === $otherObjectInfo['class'])
					{
						$relations[] = $foundRelation;
					}
				}
			}
		}
		
		return $relations;
	}
}
