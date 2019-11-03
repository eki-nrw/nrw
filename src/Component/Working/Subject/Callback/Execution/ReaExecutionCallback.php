<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Callback\Execution;

use Eki\NRW\Mdl\Working\Subject\Callback\Execution\ExecutionCallback;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ReaExecutionCallback extends ExecutionCallback
{
	/**
	* @inheritdoc
	*/
	public function getCallbackType()
	{
		return 'execution.rea';
	}
}
