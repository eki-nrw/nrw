<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Callback\Plan;

use Eki\NRW\Component\Working\PlanItem\ProcessExecutePlanItemInterface;
use Eki\NRW\NRW\Base\Process\Process\ProcessInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessExecutePlanCallback extends ExecutePlanCallback
{
	/**
	* @inheritdoc
	*/
	public function getCallbackType()
	{
		return 'plan.execute.process';
	}

	protected function addPlanItemSupport($type, $data)
	{
		return $data instanceof ProcessExecutePlanItemInterface;
	}

	protected function addProcess($type, $data)
	{
		$this->getPlan()->setSolution($data);
	}

	protected function addProcessSupport($type, $data)
	{
		return $data instanceof ProcessInterface;		
	}
}
