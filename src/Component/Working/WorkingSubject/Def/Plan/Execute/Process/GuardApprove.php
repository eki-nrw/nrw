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

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class GuardApprove extends AbstractAim
{
	/**
	* @inheritdoc
	* 
	*/	
	protected function onAim($name, $subject, array $contexts)
	{
		// A plan is determined "prepared" if all plan items are prepared
		foreach($subject->getPlanItems() as $planItem)
		{
			$ws = $this->getTool()->getWorkingSubject(Definitions::WORKING_TYPE, $planItem);
			
			//$planItem->isCurrentState(Definitions::WORKING_TYPE, Definitions::WP_PREPARED)
			
			if (null === $ws or !$ws->can(Definitions::WA_APPROVE, $contexts))
			{
				// if one of plan item cannot be approved, plan is blocked to advance
				return array('guard_blocked' => true);
			}
		}
	}
}
