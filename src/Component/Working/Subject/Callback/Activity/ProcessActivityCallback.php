<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Callback\Activity;

use Eki\NRW\Mdl\Working\Subject\Callback\Activity\ActivityCallback;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessActivityCallback extends ActivityCallback
{
	/**
	* @inheritdoc
	*/
	public function getCallbackType()
	{
		return 'activity.process';
	}
	
	protected function getProcess()
	{
		return $this->getActivity()->getProcess();
	}
}
