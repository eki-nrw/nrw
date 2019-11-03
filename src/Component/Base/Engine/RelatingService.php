<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine;

use Eki\NRW\Component\Relating\Relation\RelationInterface;
use Eki\NRW\Component\Relating\Relation\RelationshipInterface;
use Eki\NRW\Component\Relating\Relation\GroupInterface;

use Eki\NRW\Component\Relating\ContextDiagram\FilterInterface;
use Eki\NRW\Component\Relating\ContextDiagram\ContextInterface;

/**
 * Relating Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface RelatingService
{
	/**
	* Create a relation
	* 
	* @param string $identifier
	* @param string $type
	* 
	* @return \Eki\NRW\Component\Relating\Relation\RelationInterface
	*/
	public function createRelation($identifier, $type);

	/**
	* Load a relation by id
	* 
	* @param mixed $relationId
	* 
	* @return \Eki\NRW\Component\Relating\Relation\RelationInterface
	*/
	public function loadRelation($relationId);
	
	/**
	* Delete the given relation
	* 
	* @param \Eki\NRW\Component\Relating\Relation\RelationInterface $relation
	* 
	* @return void
	*/
	public function deleteRelation(RelationInterface $relation);
	
	/**
	* Update the given relation to persistent storage
	* 
	* @param \Eki\NRW\Component\Relating\Relation\RelationInterface $relation
	* 
	* @return void
	*/
	public function updateRelation(RelationInterface $relation);

	/**
	* Link the given object with the given other object by the relationship
	* 
	* @param \Eki\NRW\Component\Relating\Relation\RelationshipInterface $relationship
	* @param object $object
	* @param object $otherObject
	* 
	* @return \Eki\NRW\Component\Relating\Relation\RelationshipInterface
	*/
	public function linkRelationship(RelationshipInterface $relationship, $object, $otherObject);
	
	/**
	* Unlink the given relationship from their objects
	* 
	* @param \Eki\NRW\Component\Relating\Relation\RelationshipInterface $relationship
	* 
	* @return void
	*/
	public function unlinkRelationship(RelationshipInterface $relationship);

	/**
	* Grouping the given group object with the member objects
	* 
	* @param \Eki\NRW\Component\Relating\Relation\GroupInterface $group
	* @param object $groupObject
	* @param object $otherObjects
	* 
	* @return \Eki\NRW\Component\Relating\Relation\GroupInterface
	*/
	public function grouping(GroupInterface $group, $groupObject, array $otherObjects);
	
	/**
	* Ungrouping a group
	* 
	* @param \Eki\NRW\Component\Relating\Relation\GroupInterface $group
	* 
	* @return \Eki\NRW\Component\Relating\Relation\GroupInterface
	*/
	public function ungrouping(GroupInterface $group);

	/**
	* Add an object to group
	* 
	* @param \Eki\NRW\Component\Relating\Relation\GroupInterface $group
	* @param object $object
	* @param string|null $key Key name of object
	* 
	* @return \Eki\NRW\Component\Relating\Relation\GroupInterface
	*/
	public function addToGroup(GroupInterface $group, $object, $key = null);

	/**
	* Remove an object from group
	* 
	* @param \Eki\NRW\Component\Relating\Relation\GroupInterface $group
	* @param object $object
	* 
	* @return \Eki\NRW\Component\Relating\Relation\GroupInterface
	*/
	public function removeFromGroup(GroupInterface $group, $object);

	/**
	* Gets all relations of the given identifier and the given type that have the given base object
	* 
	* @param object $baseObject
	* @param string|null $identifier Null if all identifiers
	* @param string|null $type Null if all types
	* @param int $offset
	* @param int $limit
	* 
	* @return \Eki\NRW\Component\Relating\Relation\RelationInterface[]
	*/
	public function getRelations($baseObject, $identifier = null, $type = null, $offset = 0, $limit = 25);

	/**
	* Gets a relation of the given identifier and the given type that has the given base object
	* 
	* @param object $baseObject
	* @param string $identifier
	* @param string $type
	* 
	* @return \Eki\NRW\Component\Relating\Relation\RelationInterface
	*/
	public function getRelation($baseObject, $identifier, $type);

	/**
	* Gets all relations of the given identifier and the given type that has the given member object
	* 
	* @param object $otherObject
	* @param string $identifier
	* @param string $type
	* @param int $offset
	* @param int $limit

	* @return \Eki\NRW\Component\Relating\Relation\RelationInterface[]
	*/
	public function getRelationsOf($otherObject, $identifier, $type, $offset = 0, $limit = 25);
	
	/**
	* Get a new context diagram filter
	* 
	* @param object|null $central
	* @param array|null $relationTypes
	* @param array|null $entityClasses
	* 
	* @return \Eki\NRW\Component\Relating\ContextDiagram\FilterInterface
	*/
	public function newContextDiagramFilter($central = null, $relationTypes = null, $entityClasses = null);

	/**
	* Get context diagram
	* 
	* @param \Eki\NRW\Component\Relating\ContextDiagram\FilterInterface $contextDiagramFilter
	* 
	* @return \Eki\NRW\Component\Relating\ContextDiagram\ContextInterface
	*/	
	public function getContextDiagram(FilterInterface $contextDiagramFilter);
}
