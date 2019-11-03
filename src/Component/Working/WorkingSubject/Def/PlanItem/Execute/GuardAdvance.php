<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def\PlanItem\Execute;

use Eki\NRW\Component\Working\WorkingSubject\Def\AbstractAim;
use Eki\NRW\Component\Working\WorkingSubject\Def\Definitions;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class GuardAdvance extends AbstractAim
{
	/**
	* @inheritdoc
	* 
	*/	
	protected function onAim($name, $subject, array $contexts)
	{
		//$planItem = $subject;
		
		$plan = $this->getTool()->getContinuedSubject(
			$subject, 
			Definitions::WORKING_TYPE, 
			Definitions::WC_SOURCE, 
			true
		);
		
		if ($plan !== null and !$plan->isCurrentState(Definitions::WORKING_TYPE, Definitions::WP_PREPARED))  // ????
		{
			return array( 'guard_blocked' => true );
		}
	}
}
