<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Relating\ContextDiagram;

use Eki\NRW\Common\Common\ValueObject;
use Eki\NRW\Component\Relating\Relation\RelationInterface;

/**
 * Context Diagram implemntation.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class Context extends ValueObject implements ContextInterface
{
	/**
	* @var object
	*/
	protected $central;

	/**
	* @var RelationInterface[]
	*/
	protected $relations;
	
	public function __construct($central, array $relations)
	{
		if (!is_object($central))
			throw new \InvalidArgumentException("Parameter 'central' is not object.");
			
		$this->central = $central;
		
		foreach($relations as $relation)
		{
			if (!$relation instanceof RelationInterface)
				throw new \InvalidArgumentException(sprintf(
					"One of relation is not instance of %s. Given %s",
					RelationInterface::class,
					get_class($relation)
				));
		}
		
		$this->relations = $relations;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function getCentral()
	{
		return $this->central;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function getRelations($types = null)
	{
		if ($types === null)
			return $this->relations;
			
		$relations = [];
		foreach($types as $relationType => $typeOfRelationType)
		{
			foreach($this->relations as $rel)
			{
				if (is_string($typeOfRelationType))
					$typeOfRelationType = array($typeOfRelationType);
					
				if (!is_array($typeOfRelationType))
					throw new \InvalidArgumentException("????");

				if ($rel->getRelationType() !== $relationType)
					continue;
					
				foreach($typeOfRelationType as $type)
				{
					if ($rel->getType() === $type)
					{
						$relations[] = $rel;
						break;
					}
				}
			}
			
			return $relations;
		}		
	}
}
