<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Working;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Returns working subject persistent handler
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Working\WorkingSubject\Handler
	*/
	public function workingSubjectHandler();
	
	/**
	* Returns subject persistence handler
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Working\Subject\Handler
	*/
	public function subjectHandler();
}
