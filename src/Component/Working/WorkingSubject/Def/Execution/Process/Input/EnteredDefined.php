<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def\Execution\Process\Input;

use Eki\NRW\Component\Working\WorkingSubject\Def\AbstractAim;
use Eki\NRW\Component\Working\WorkingSubject\Def\Definitions;

use Eki\NRW\Component\Working\Execution\InputProcessExecution;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class EnteredPublished extends AbstractAim
{
	/**
	* @inheritdoc
	* 
	*/	
	protected function onAim($name, $subject, array $contexts)
	{
		if (!$subject instanceof InputProcessExecution)
			return;
			
		$activity = $this->getTool()->getContinuedSubject(
			$subject   // execution.process.input
			Definitions::WORKING_TYPE,
			Definitions::WC_ADVANCE,
			true
		);
		
		$this->getTool()->continuingSubjects(
			$activity->getProcess(),
			$process,
			Definitions::WORKING_TYPE,
			Definitions::WC_ADVANCE
		);
	}
}
