<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine;

use Eki\NRW\Component\Base\Engine\Working\SubjectService;
use Eki\NRW\Component\Base\Engine\Working\WorkingSubjectService;

/**
 * Working Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface WorkingService
{
	/**
	* Returns working subject service
	* 
	* @return \Eki\NRW\Component\Base\Engine\Working\WorkingSubjectService
	*/
	public function workingSubjectService();

	/**
	* Returns subject service
	* 
	* @return \Eki\NRW\Component\Base\Engine\Working\SubjectService
	*/
	public function subjectService();
}
