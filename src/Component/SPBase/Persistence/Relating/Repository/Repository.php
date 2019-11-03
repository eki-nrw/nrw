<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Relating\Repository;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Repository
{
	/**
	* Find all relations of the given identifier and the given type that has the base/other object $baseObject
	* 
	* @param array $objectInfo
	* @param string $identifier
	* @param string|null $type
	* @param int $offset
	* @param int $limit
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Relating\Relation[]
	*/
	public function findAllRelations(array $objectInfo, $identifier, $type, $offset, $limit);

	/**
	* Get all relations of the given identifier and the given type between the given base object and the given other object
	*
	* + ObjectInfo:
	* 	'id' => persistence object id 
	* 	'class' => class of object
	* 
	* @param array $baseObjectInfo Base object info.
	* @param array $otherObjectInfo Other object ìno
	* @param string $identifier
	* @param string $type
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Relating\Relation[]
	*/
	public function getRelationsBetween(array $baseObjectInfo, array $otherObjectInfo, $identifier, $type);
}
