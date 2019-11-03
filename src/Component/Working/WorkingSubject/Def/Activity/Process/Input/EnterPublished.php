<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def\Activity\Process\Input;

use Eki\NRW\Component\Working\WorkingSubject\AbstractAim;
use Eki\NRW\Component\Working\WorkingSubject\Def\Definitions;
use Eki\NRW\Component\Working\Activity\InputProcessActivity;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class EnterPublished extends AbstractAim
{
	/**
	* @inheritdoc
	* 
	*/	
	protected function onAim($name, $subject, array $contexts = [])
	{
		if (!$subject instanceof InputProcessActivity)
		{
			$this->Logger()->warning(sprintf("Subject is not %s.", InputProcessActivity::class));
			return null;
		}

		$planItem = $this->getTool()->getContinuedSubject(
			$subject,   // activity.process.input subject
			Definitions::WORKING_TYPE,
			Definitions::WC_ADVANCE,
			true
		);
		
		if (null === $planItem)
		{
			$this->Logger()->error("No back-advanced plan item.");
			return null;
		}

		/**
		* 
		* @var \Eki\NRW\Mdl\Processing\Solution\Solution\DelegateSolution
		* 
		*/		
		$delegateSolution = $planItem->getPlan()->getSolution();

		$delegateSolution->solveByParams(
			array(
				'situation' => array(
					'stepKey' => 'in_process_any',
					'acting' => $subject,
					'actingKey' => $planItem->getPlan()->getKeyOfPlanItem($planItem),
					'contexts' => $contexts
				)
			)
		);
	}
}
