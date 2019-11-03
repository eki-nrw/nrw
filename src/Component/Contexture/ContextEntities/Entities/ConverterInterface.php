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

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
interface ConverterInterface
{
	/**
	* Convert the pair of scope/level in context to matched relationship type
	* 
	* @param string $scope
	* @param string $level
	* 
	* @return string
	*/
	public function toRelationshipType($scope, $level);
}
