<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def\PlanItem\Execute\Exchange;

use Eki\NRW\Component\Working\WorkingSubject\Def\AbstractAim;
use Eki\NRW\Component\Working\WorkingSubject\Def\Definitions;
use Eki\NRW\Component\Working\PlanItem\ExchangeExecutePlanItemInterface;
use Eki\NRW\Mdl\Working\Subject\DirectorInterface as SubjectDirector;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class EnteredDefined extends AbstractAim
{
	protected function onAim($name, $subject, array $contexts)
	{
		if (!$subject instanceof ExchangeExecutePlanItemInterface)
			return;
		
		$tool = $this->getTool();

		/**
		* @var \Eki\NRW\Component\Exchanging\ExchangeItem\ExchangeItemInteface
		*/
		$exchangeItem = $tool->getContinuedSubject(
			$subject,           // planitem.execute.exchange
			Definitions::WORKING_TYPE,
			Definitions::WC_ADVANCE,
			true
		);
		
		if (null === $exchangeItem)
			return;

		$plan = $tool->getContinuedSubject(
			$exchangeItem->getEchange(),           // exchange
			Definitions::WORKING_TYPE,
			Definitions::WC_ADVANCE,
			true
		);
		
		if (null === $plan)
		{
			$this->Logger()->warning("No exchange plan.");
			return null;
		}

		if (null === ($subjectDirector = $this->getOption('subjectDirector'))
		{
			$this->Logger()->error("No subject director specified in options.");
			return null;
		}

		$builder = $subjectDirector->getBuilder($tool->getSubjectType($plan))
			->setObject($plan)
			->add(
				'plan_item', 
				null, 
				$subject       // plan item
			)
		;
	}
}
