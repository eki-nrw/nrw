<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def;

use Eki\NRW\Component\Working\WorkingSubject\Def\AbstractAim;
use Eki\NRW\Component\Working\WorkingSubject\Def\Definitions;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class CompletedResume extends AbstractAim
{
	/**
	* @inheritdoc
	* 
	*/	
	protected function _supportType($subject)
	{
		return true;  // any type
	}
	
	protected function onAim($name, $subject, array $contexts)
	{
		$advancedSubject = $this->getTool()->getContinuedSubject(
			$subject, 
			Definitions::WORKING_TYPE, 
			Definitions::WC_ADVANCE
		);
		
		if ($advancedSubject === null)
		{
			$this->Logger()->info("No advanced subject.");
			return;
		}
		
		// When the subject	has been not suspended, his/her advanced subject should be not suspended also
		$this->getTool()
			->getWorkingSubject(Definitions::WORKING_TYPE, $advancedSubject)
			->action(Definitions::WA_RESUME, $contexts)
		;
	}
}
