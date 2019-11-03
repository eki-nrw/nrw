<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def\Execution\Rea;

use Eki\NRW\Component\Working\WorkingSubject\Def\AbstractAim;
use Eki\NRW\Component\Working\WorkingSubject\Def\Definitions;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class EnteredPublished extends AbstractAim
{
	/**
	* @inheritdoc
	* 
	*/	
	protected function onAim($name, $subject, array $contexts)
	{
		//if (!isset($contexts['solutionProvider']))
		//	return;

		// get solution provider from system
		//$solutionProvider = $contexts['solutionProvider'];

		$activity = $this->getTool()->getContinuedSubject(
			$subject   // execution.rea
			Definitions::WORKING_TYPE,
			Definitions::WC_ADVANCE,
			true
		);

		$planItem = $this->getTool()->getContinuedSubject(
			$activity,
			Definitions::WORKING_TYPE,
			Definitions::WC_ADVANCE,
			true
		);
		
		$solutionContext = $planItem->getPlan()->getSolution();
		if ($subject->getExecutionType()->is('input'))
			$steps = [ 1 ];
		else if ($subject->getExecutionType()->is('output'))
			$steps = [ 2, 3 ];
		else
			throw new \LogicException("?????????????????");
		foreach($steps as $stepNo)
		{
			$solutionContext
				->get('situation')
					->add('stepNo', $stepNo)
					->add('execution', $subject)
					->add('contexts', $contexts)
			;
			$solution = $solutionProvider->provide($solutionContext);
			$solution->solve($solutionContext);				
		}
	}
}
