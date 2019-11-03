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

use Eki\NRW\Component\Working\WorkingSubject\Def\AbstractAim;
use Eki\NRW\Component\Working\WorkingSubject\Def\Definitions;
use Eki\NRW\Component\Working\Activity\OutputProcessActivity;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class CompletedAdvance extends AbstractAim
{
	/**
	* @inheritdoc
	* 
	*/	
	protected function onAim($name, $subject, array $contexts = [])
	{
		if (!$subject instanceof OutputProcessActivity)
		{
			$this->Logger()->warning(sprintf("Subject is not %s.", InputProcessActivity::class));
			return null;
		}

		$plan = $this->getTool()->getContinuedSubject(
			$subject   // activity.process.output subject
			Definitions::WORKING_TYPE,
			Definitions::WC_ADVANCE,
			true
		);
		
		if (null === $plan)
		{
			$this->Logger()->error("No back-advanced plan.");
			return null;
		}
		
		/**
		* 
		* @var \Eki\NRW\Mdl\Processing\Solution\Solution\DelegateSolution
		* 
		*/		
		$delegateSolution = $plan->getSolution();
		$delegateSolution->solveByParams(
			array(
				'situation' => array(
					'stepKey' => "process_out",
					'acting' => $subject,
					'contexts' => $contexts
				)
			)
		);
	}
}
