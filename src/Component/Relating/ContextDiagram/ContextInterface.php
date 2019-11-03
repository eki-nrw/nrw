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
 * Context Diagram interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface ContextInterface
{
	/**
	* Returns central object
	* 
	* @return object
	*/
	public function getCentral();
	
	/**
	* Returns the relations of the central object
	* 
	* @param array|null $types Null if all relation types
	* 
	* @return \Eki\NRW\Component\Relating\Relation\RelationInterface[]
	*/
	public function getRelations($types = null);
}
