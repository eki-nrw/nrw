<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Relating;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create a new relation entity in a storage engine
	* 
	* @param string $identifier
	* @param string $type
	* 
	* @return \Eki\NRW\Component\Base\Relating\Relation\Relation
	*/
	public function createRelation($identifier, $type);
	
	/**
	* Load the given relation
	* 
	* @param int|string $relationIdd
	* 
	* @return \Eki\NRW\Component\Base\Relating\Relation\Relation
	*/
	public function loadRelation($relationId);

	/**
	* Delete the given relation
	* 
	* @param \Eki\NRW\Component\Base\Relating\Relation\Relation $relation
	* 
	* @return void
	*/	
	public function deleteRelation(Relation $relation);
	
	/**
	* Update the given relation
	* 
	* @param \Eki\NRW\Component\Base\Relating\Relation\Relation $relation
	* 
	* @return void
	*/
	public function updateRelation(Relation $relation);

	/**
	* Gets all relations of the given identifier and the given type that have the given base/other object(s)
	* 
	* @param array $objectInfo
	* @param string|null $identifier Null if all identifiers
	* @param string|null $type Null if all types
	* @param int $offset
	* @param int $limit
	* 
	* @return \Eki\NRW\Component\Base\Relating\Relation\Relation[]
	*/	
	public function getRelations(array $objectInfo, $identifier = null, $type = null, $offset = 0, $limit = 25);

	/**
	* Gets all relations of the given identifier and the given type that are between the given base object and the given other object
	* 
	* @param array $baseObjectInfo
	* @param array $otherObjectInfo
	* @param string|null $identifier Null if all identifiers
	* @param string|null $type Null if all types
	* 
	* @return \Eki\NRW\Component\Base\Relating\Relation\Relation[]
	*/	
	public function getRelationsBetween(array $objectInfo, array $otherObjectInfo, $identifier = null, $type = null);
}
