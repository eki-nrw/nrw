<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Working\WorkingSubject;

use Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface;
use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create new working subject of the given working type
	* 
	* @param string $workingType
	* 
	* @return \Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface
	*/
	public function createWorkingSubject($workingType);
	
	/**
	* Load working subject of the given id
	* 
	* @param mĩed $id
	* 
	* @return \Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface
	*/
	public function loadWorkingSubject($id);

	/**
	* Find the couple of working subject and subject
	* 
	* @param string $workingType
	* @param object $subject
	* 
	* @return \Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface|null
	*/
	public function findWorkingSubject($workingType, $subject);
	
	/**
	* Update the working subject to persistent storage
	* 
	* @param \Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface $workingSubject
	* 
	* @return void
	*/
	public function updateWorkingSubject(WorkingSubjectInterface $workingSubject);
	
	/**
	* Delete the working subject from persistent storage
	* 
	* @param \Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface $workingSubject
	* 
	* @return void
	*/
	public function deleteWorkingSubject(WorkingSubjectInterface $workingSubject);
}
