<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def\Plan\Execute\Process;

use Eki\NRW\Component\Working\WorkingSubject\Def\AbstractAim;
use Eki\NRW\Component\Working\WorkingSubject\Def\Definitions;
use Eki\NRW\Component\Working\Plan\ProcessExecutePlan;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class EnterTerminated extends AbstractAim
{
	/**
	* @inheritdoc
	* 
	*/	
	protected function onAim($name, $subject, array $contexts)
	{
		if (!$subject instanceof ProcessExecutePlan)
			return;

		$tool = $this->getTool();

		// From advance subject
		$activity = $tool->getContinuedSubject(
			$subject   // plan.execute.process
			Definitions::WORKING_TYPE,
			Definitions::WC_ADVANCE,
			true
		);
		
		if (null !== $activity)
		{
			$subject->getObjective()->setOutcome($activity->getObjectItem());
		}		

		// To back-sourced subject
		$planItem = $tool->getContinuedSubject(
			$subject   // plan.execute.process
			Definitions::WORKING_TYPE,
			Definitions::WC_SOURCE,
			false
		);

		if (null !== $planItem)
		{
			$planItem->setObjectItem($subject->getObjective()->getOutcome());
		}
	}
}
