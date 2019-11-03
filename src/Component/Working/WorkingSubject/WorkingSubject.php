<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject;

use Eki\NRW\Mdl\Working\WorkingSubject\WorkingSubject as BaseWorkingSubject;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class WorkingSubject extends BaseWorkingSubject implements WorkingSubjectInterface
{
	use
		ResTrait
	;
	
	protected $subjectId;
	
	public function getSubjectId()
	{
		return $this->subjectId;
	}

	public function setSubject($subject)
	{
		$this->subjectId = $subject->getId();
		
		parent::setSubject($subject);	
	}	
}
