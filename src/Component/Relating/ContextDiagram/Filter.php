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

/**
 * Context Diagram filter implmentation.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class Filter implements FilterInterface
{
	private $central;
	private $types;
	private $classes;
	
	/**
	* @inheritdoc
	* 
	*/
	public function setCentral($object)
	{
		if (!is_object($object))
			throw new \InvalidArgumentException("Parameter 'object' must be an object.");
			
		$this->central = $object;
		
		return $this;
	}
	
	/**
	* Sets the relation types (group, relationshjip, ...)
	* 
	* @param array|null $relationTypes Null if all
	* 
	* @return this
	*/
	public function setRelationTypes($relationTypes = null)
	{
		if ($relationTypes === null)
		{
			$this->types = null;
		}
		else
		{
			if (!is_array($relationTypes))
				throw new \InvalidArgumentException("Parameter 'relationTypes' must be an array or null.");
			
			$this->types = array();	
			foreach($relationTypes as $relationType)
			{
				$this->types[$relationType] = array();
			}
		}		
		
		return $this;
	}
	
	/**
	* Sets the types of the relation type
	* 
	* @param array|null $typesOfRelations Null if all
	* 
	* @return this
	*/
	public function setTypesOfRelations($relationType, $typesOfRelationType = null)
	{
		if (!isset($this->types[$relationType]))
			throw new \InvalidArgumentException("No relation type '$relationType' specified.");
		
		if ($typesOfRelationType === null)
			$this->types[$relationType] = null;
		else
		{
			if (!is_array($typesOfRelationType))
				throw new \InvalidArgumentException("Parameter 'typesOfRelatioType' must be an array or null");
				
			$this->types[$relationType] = $typesOfRelationType;
		}
		
		return $this;
	}
	
	/**
	* Sets the object classes of the diagram entities
	* 
	* @param array|null $classes Null if any class
	* 
	* @return this
	*/
	public function setEntityClasses($classes = null)
	{
		if ($classes === null)
		{
			$this->classes = null;	
		}
		else
		{
			if (is_string($classes))
				$classes = array($classes);
			if (!is_array($classes))
				throw new \InvalidArgumentException("Parameter 'classes' must be an array.");
				
			foreach($classes as $class)
			{
				if (!class_exists($class))
					throw new \InvalidArgumentException("Class '$class' not found.");
			}	
				
			$this->classes = $classes;
		}
			
		return $this;
	}
	
	/**
	* Returns filter configuration
	* 
	* @return array
	*/
	public function getFilter()
	{
		$config = array();
		
		if ($this->central !== null)
			$config['central'] = $this->central;
		if ($this->types !== null)
		{
			$config['relations'] = $this->types;
		}
		if ($this->classes !== null)
		{
			$config['entities'] = $this->classes;
		}
	}
}
