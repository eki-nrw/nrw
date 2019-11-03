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
abstract class EnteredAdvanced extends AbstractAim
{
	/**
	* @inheritdoc
	* 
	*/	
	protected function _supportType($subject)
	{
		return true;  // any type
	}
	
	protected function onAim($name, $subject, array $contexts = [])
	{
		$tool = $this->getTool();
		
		$advanceSubjectType = $tool->getContinuedSubjectType(
			$this->getTool()->getSubjectType($subject)
			Definitions::WORKING_TYPE, 
			Definitions::WC_ADVANCE,
			$contexts
		);
		
		if (empty($advanceSubjectType))
			return;
			
		// Create advanced subject
		if (null === ($advanceSubject = $tool->createSubject($advanceSubjectType)))
			return;
			
		// Import advance subject from import source subject	
		$tool->createWorkingSubject(Definitions::WORKING_TYPE, $advanceSubject)
			->action(
				Definitions::WA_IMPORT, 
				$contexts + array('importSource' => $subject)
		    )
		;

		// Determine continue relation between the original subject and the advance subject
		$tool->continuingSubjects(
			$subject, 
			$advanceSubject, 
			Definitions::WORKING_TYPE, 
			Definitions::WC_ADVANCE
		);
	}
}
