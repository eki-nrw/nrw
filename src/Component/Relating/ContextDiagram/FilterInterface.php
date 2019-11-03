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
 * Context Diagram filter interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface FilterInterface
{
	/**
	* Set the central object of the diagram
	* 
	* @param object $object
	* 
	* @return this
	*/
	public function setCentral($object);
	
	/**
	* Sets the relation types (group, relationship, ...)
	* 
	* @param array|null $relationTypes Null if all
	* 
	* @return this
	*/
	public function setRelationTypes($relationTypes = null);
	
	/**
	* Sets the types of the relation type
	* 
	* @param string $relationType
	* @param array|null $typesOfRelationType Null if all
	* 
	* @return this
	*/
	public function setTypesOfRelationType($relationType, $typesOfRelationType = null);
	
	/**
	* Sets the object classes of the diagram entities
	* 
	* @param array|null $classes Null if any class
	* 
	* @return this
	*/
	public function setEntityClasses($classes = null);
	
	/**
	* Returns filter configuration
	* 
	* @return array
	*/
	public function getFilter();
}
