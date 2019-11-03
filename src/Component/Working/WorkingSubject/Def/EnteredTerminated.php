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
abstract class EnteredTerminated extends AbstractAim
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
		$originalSubject = $this->getTool()
			->getContinuedSubject($subject, Definitions::WORKING_TYPE, Definitions::WC_ADVANCE, true)
		;
		
		if ($originalSubject === null)
			return;
			
		$this->getTool()
			->getWorkingSubject(Definitions::WORKING_TYPE, $originalSubject)
			->action(Definitions::WA_TERMINATE, $contexts)
		;
	}
}
