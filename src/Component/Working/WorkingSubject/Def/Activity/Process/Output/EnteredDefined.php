<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def\Activity\Process\Output;

use Eki\NRW\Component\Working\WorkingSubject\AbstractAim;
use Eki\NRW\Component\Working\WorkingSubject\Def\Definitions;
use Eki\NRW\Component\Working\Activity\OutputProcessActivity;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class EnteredDefined extends AbstractAim
{
	/**
	* @inheritdoc
	* 
	*/	
	protected function onAim($name, $subject, array $contexts = [])
	{
		if (!$subject instanceof OutputProcessActivity)
		{
			$this->Logger()->warning(sprintf("Subject is not %s.", OutputProcessActivity::class));
			return null;
		}

		$plan = $this->getTool()->getContinuedSubject(
			$subject,   // activity.process.input subject
			Definitions::WORKING_TYPE,
			Definitions::WC_ADVANCE,
			true
		);
		
		if (null === $plan)
		{
			$this->Logger()->error("No back-advanced plan.");
			return null;
		}

		$subject->setProcess($plan->getProcess());
	}
}
