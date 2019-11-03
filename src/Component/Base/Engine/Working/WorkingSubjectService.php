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
 * Working Subject Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface WorkingSubjectService
{
	/**
	* Create a new working subject of the given working type
	* 
	* @param string $workingType
	* 
	* @return \Eki\NRW\Component\Working\WorkingSubjectInterface
	* 
	* @throw
	*/
	public function createWorkingSubject($workingType);

	/**
	* Load the working subject with given id
	* 
	* @param mixed $id
	* 
	* @return \Eki\NRW\Component\Working\WorkingSubjectInterface
	* 
	* @throw
	*/	
	public function loadWorkingSubject($id);
	
	/**
	* Update the given working subject
	* 
	* @param \Eki\NRW\Component\Working\WorkingSubjectInterface $workingSubject
	* 
	* @return void
	* 
	* @throw
	*/
	public function updateWorkingSubject(WorkingSubjectInterface $workingSubject);
	
	/**
	* Delete the given working subject
	* 
	* @param \Eki\NRW\Component\Working\WorkingSubjectInterface $workingSubject
	* 
	* @return void
	* 
	* @throw
	*/
	public function deleteWorkingSubject(WorkingSubjectInterface $workingSubject);
	
	/**
	* Get a working subject by loading or creating
	* 
	* @param string $workingType
	* @param object $subject
	* 
	* @return \Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface|null
	* 
	* @throws
	*/
	public function findWorkingSubject($workingType, $subject);

	/**
	* Returns continue subject type
	* 
	* Relational subject type depends on:
	* + working type
	* + the type (opposite of other type)
	* + option of working type
	* 
	* @param string $subjectType Type of the subject
	* @param string $workingType
	* @param string $continuation
	* @param array $contexts
	* 
	* @return string Type of relational subject
	*/
	public function getContinueSubjectType($subjectType, $workingType, $continuation, array $contexts = []);

	/**
	* Returns continued subject
	* 
	* @param object $subject The subject
	* @param string $workingType
	* @param string $continuation
	* @param bool $back True if getting object. False if getting other object interms of relationship
	* 
	* @return object The relational subject
	*/
	public function getContinuedSubject($subject, $workingType, $continuation, $back = false);
	
	/**
	* Continuing the subject with the relational subject by the relation of the given working type and option
	* 
	* @param object $subject
	* @param object|array $continuedSubject
	* @param string $workingType
	* @param string $continuation
	* 
	* @return \Eki\NRW\Component\Relating\Relation\RelationInterface
	*/
	public function continuingSubjects($subject, $continuedSubject, $workingType, $continuation);
}
