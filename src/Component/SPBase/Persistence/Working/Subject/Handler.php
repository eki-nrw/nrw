<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Working\Subject;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create persistent subject
	* 
	* @param string $identifier
	* 
	* @return object
	*/
	public function createSubject($identifier);

	/**
	* Load persistent subject
	* 
	* @param mixed $subjectId
	* 
	* @return object
	*/
	public function loadSubject($subjectId);
	
	public function deleteSubject($subject);
	
	public function updateSubject($subject);
}
