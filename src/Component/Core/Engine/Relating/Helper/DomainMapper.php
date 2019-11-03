<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Relating\Helper;

use Eki\NRW\Component\Base\Persistence\Relating\Relation as PersistenceRelationInterface;

use Eki\NRW\Component\Relating\Relation\RelationInterface;

use Eki\NRW\Common\Res\Factory\Factory;

use Eki\NRW\Common\Relations\Node;
use Eki\NRW\Component\Core\Engine\Helper\ObjectLoaderInterface;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentValue;

use ReflectionClass;

/**
 * Internal service to map Relation objects between Base API and Persistence values.
 *
 * @internal Meant for internal use by Engine.
 */
class DomainMapper
{
	/**
	* @var Factory
	*/
	protected $factory;
	
	/**
	* @var ObjectLoaderInterface
	*/
	protected $objectLoader;
	
	/**
	* @var ReflectionClass[]
	*/
	protected $reflections = [];
	
	/**
	* @var TypeMeaningHelper
	*/
	protected $typeMeaningHelper;
	
	public function __construct(array $factorySettings, ObjectLoaderInterface $objectLoader)
	{
		$registries = [];
		foreach($factorySettings as $type => $className)
		{
			if (!class_exists($className))
				throw new InvalidArgumentException(sprintf(
					"factorySettings", "Factory settings classname of type %s not exists: %s.",
					$type, $className
				));
			$registries[$type] = $className;
			
			$this->reflections[$type] = new ReflectionClass($className);
		}
		$this->factory = new Factory($registries);

		$this->objectLoader = $objectLoader;
	}
	
	public function buildRelationDomainObject(
		PersistenceRelationInterface $psRelation, 
		RelationInterface $relation = null
	)
	{
		$factoryType = ResHelper::factoryType($psRelation->identifier, $psRelation->type);
		if ($relation === null)
		{
			if (!$this->factory->support($factoryType))
				throw new InvalidArgumentException(
					"psRelation", 
					sprintf(
						"Identifier '%s' and '%s' invalid.", 
						$psRelation->identifier,
						$psRelation->type
					)
				);

			$meaningTypes = ResHelper::meaningTypes($psRelation->type);
			$relation = $this->factory->createNew(
				$factoryType, 
				!empty($meaningTypes) ? $meaningTypes : null
			);
		}
	
		if ($psRelation->identifier !== $relation->getRelationType())
			throw new InvalidArgumentException(sprintf(
				"Persistence relation identifier is not same to domain relation identifier. %s versus %s.",
				$psRelation->identifier,
				$relation->getRelationType()
			));
		
		//$r = new ReflectionClass($relation);
		$r = $this->reflections[$factoryType];
		$p = $r->getProperty('id');
		$p->setAccessible(true);
		$p->setValue($relation, $psRelation->id);
		
		$relation->setName($psRelation->name);
		
		// Base
		if (null !== ($psBase = $psRelation->getBase()))
		{
			$baseNode = new Node();
			$baseNode->setObject($this->getObjectIndirect($psBase->getBase(), $this->objectLoader));
			$relation->setBase($baseNode);	
		}
		
		// Others
		foreach($psRelation->getOthers() as $psKey => $psOther)
		{
			$otherNode = new Node();
			$otherNode->setObject($this->getObjectIndirect($psOther, $this->objectLoader));
			$relation->addOther($otherNode, $psKey);
		}
		
		// Parameters
		$relation->setParameters($psRelation->getParameters());
		
		return $relation;
	}
	
	public function buildRelationPersistObject(
		RelationInterface $relation, 
		PersistenceRelationInterface $psRelation
	)
	{
		// base
		if (null !== ($baseNode = $relation->getBase()) and null !== ($baseObject = $baseNode->getObject()))
			$psRelation->setBase($baseObject);
		else
			$psRelation->setBase();
		
		// others
		$psRelation->setOthers();
		foreach($relation->getOthers() as $key => $otherNode)
		{
			if (null !== ($obj = $otherNode->getObject()))
				$psRelation->addOther($obj,	$key);
		}
		
		// parammeters
		$psRelation->setParameters($relation->getParameters());

		return $psRelation;		
	}

	/**
	* Gets object through object loader
	* 
	* @param array|object $obj
	* @param ObjectLoaderInterface $objectLoader
	* 
	* @return object
	*/	
	private function getObjectIndirect($obj, ObjectLoaderInterface $objectLoader)
	{
		if (is_array($obj) and isset($obj['id']) and isset($obj['class']))
		{
			return $objectLoader->loadObject($obj);
		}
		else if (is_object($obj))
		{
			return $obj;
		}
	}
}
