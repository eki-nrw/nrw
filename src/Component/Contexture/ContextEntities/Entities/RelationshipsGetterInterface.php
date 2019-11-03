<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Contexture\ContextEntities\Entities;

use Eki\NRW\Common\Relations\Relationship\RelationshipInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
interface RelationshipsGetterInterface
{
	/**
	* Gets all relationships of the given boundary object
	* 
	* @param object $boundary
	* 
	* @return RelationshipInterface[]
	*/
	public function getAll($boundary);
	
	/**
	* Gets the relationships of the type $relationshipType of the given object
	* 
	* @param object $boundary
	* @param string $relationshipType
	* 
	* @return RelationshipInterface[]
	*/
	public function get($boundary, $relationshipType);
}
