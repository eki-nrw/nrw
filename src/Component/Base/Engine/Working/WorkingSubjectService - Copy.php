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
	* Create a new working subject
	* 
	* @param string $workingType
	* 
	* @return \Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface
	*/
	public function createWorkingSubject($workingType);

	/**
	* Load working subject by the given id
	* 
	* @param string $workingId
	* 
	* @return \Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface|null
	*/
	public function loadWorkingSubject($workingId);

	/**
	* Find the working subject of the working type that has the given subject
	* 
	* @param string $workingType
	* @param object $subject
	* 
	* @return \Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface|null
	* @throws
	*/
	public function findWorkingSubject($workingType, $subject);

	/**
	* Delete the given working subject
	* 
	* @param \Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface $workingSubject
	* 
	* @return void
	* @throws
	* 
	*/
	public function deleteWorkingSubject(WorkingSubjectInterface $workingSubject);
	
	/**
	* Update the given working subject
	* 
	* @param \Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface $workingSubject
	* 
	* @return void
	* @throws 
	* 
	*/
	public function updateWorkingSubject(WorkingSubjectInterface $workingSubject);
	
	/**
	* Returns relational subject type
	* 
	* Relational subject type depends on:
	* + working type
	* + the type (opposite of other type)
	* + option of working type
	* 
	* @param string $subjectType Type of the subject
	* @param string $workingType
	* @param string $jump
	* @param array $contexts
	* 
	* @return string Type of relational subject
	*/
	public function getRelationSubjectType($subjectType, $workingType, $jump, array $contexts = []);

	/**
	* Returns relational subject
	* 
	* @param object $subject The subject
	* @param string $workingType
	* @param string $jump
	* @param bool $back True if getting object. False if getting other object interms of relationship
	* 
	* @return object The relational subject
	*/
	public function getRelationSubject($subject, $workingType, $jump, $back = false);
	
	/**
	* Relating the subject with the relational subject by the relation of the given working type and option
	* 
	* @param object $subject
	* @param object|array $relationSubject
	* @param string $workingType
	* @param string $jump
	* 
	* @return \Eki\NRW\Component\Relating\Relation\RelationInterface
	*/
	public function relatingSubjects($subject, $relationSubject, $workingType, $jump);
}
