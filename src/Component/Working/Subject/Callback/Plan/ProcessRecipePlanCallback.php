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

use Eki\NRW\NRW\Base\Process\Process\ProcessTypeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessRecipePlanCallback extends RecipePlanCallback
{
	/**
	* @inheritdoc
	*/
	public function getCallbackType()
	{
		return 'plan.recipe.process';
	}

	protected function addProcessType($type, $data)
	{
		$this->getPlan()->setSolution($data);
	}

	protected function addProcessTypeSupport($type, $data)
	{
		return $data instanceof ProcessTypeInterface;
	}
}
