<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def\Solution;

use Eki\NRW\Component\Processing\Solution\Solution\ByStep\Algorithm;
use Eki\NRW\Component\Processing\Solution\Context\ContextInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class SourceAlgorightm extends Algorithm
{
	public function __construct()
	{
		$step = new Step(
			function (ContextInterface $context) {
				$situation = $context->get("situation");
				$sourcePlan = $situation['acting'];
				$workingSubjectTool = $situation['contexts']['workingSubjectTool'];
				
				$sourceDelegateSolution = $sourcePlan->getSolution();
				$sourceProcess = $sourceDelegateSolution->get("solving")['solver'];
				
				$planItem = $workingSubjectTool->getContinuedSubject(
					$sourcePlan,    // plan
					Definitions::WORKING_TYPE, 
					Definitions::WC_SOURCE,
					true
				);
				$plan = $planItem->getPlan();
				$inputActivity = $workingSubjectTool->getContinuedSubject(
					$plan,    // plan
					Definitions::WORKING_TYPE, 
					Definitions::WC_ADVANCE
				);

				$delegateSolution = $plan->getSolution();
				$process = $delegateSolution->get("solving")['solver'];
				
				$toKey = $plan->getKeyOfPlanItem($planItem);
				$sourceProcess->pipe($process, $toKey, $situation['contexts']);
				
				$workingSubjectTool->continuingSubjects(
					$sourceProcess,
					$process,
					'processing.process.source', 
					Definitions::WC_PIPE
				);

				$workingSubjectTool->continuingSubjects(
					$sourceProcess->getOutput()->getSubject(),
					$inputActivity,
					Definitions::WORKING_TYPE, 
					Definitions::WC_PIPE
				);
			}
		);
		
		parent::__construct(array(), array($step));
	}
}
