<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\WorkingSubject;

use Eki\NRW\Mdl\Working\WorkingSubject\AbstractActionHandler;
use Eki\NRW\Mdl\Working\PlanItemInterface;
use Eki\NRW\Mdl\Working\PlanInterface;
use Eki\NRW\Common\Compose\ObjectItem\DelegateComposer;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class SourceActionHandler extends AbstractActionHandler
{
	/**
	* @var DelegateComposer
	*/
	protected $delegateComposer;

	/**
	* @inheritdoc
	*/
	public function support($subject, $actionName)
	{
		return $subject instanceof PlanItemInterface;
	}
	
	protected function onPrepare($subject, array $contexts)
	{
		$planItem = $subject;

		if (!isset($contexts['plan']))
			return;
					
		if (null === ($plan = $contexts['plan']) or !$plan instanceof PlanInterface)
			return;
		
		$planItem->setObjectItem($this->delegateComposer->compose($plan));
	}
}
