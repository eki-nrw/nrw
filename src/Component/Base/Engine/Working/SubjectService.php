<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Working;

use Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface;

/**
 * Subject Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface SubjectService
{
	/**
	* Create subject by identifier and kind
	* 
	* @param string $identifier
	* 
	* @return object
	*/
	public function createSubject($identifier);

	/**
	* Load subject
	* 
	* @param mixed $subjectId
	* 
	* @return object
	*/
	public function loadSubject($subjectId);

	/**
	* Delete subject 
	* 
	* @param object $subject
	* 
	* @return void
	*/
	public function deleteSubject($subject);
	
	/**
	* Update subject
	* 
	* @param object $subject
	* 
	* @return void
	*/
	public function updateSubject($subject);
}
