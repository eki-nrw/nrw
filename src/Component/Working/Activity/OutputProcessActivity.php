<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Activity;

use Eki\NRW\Mdl\Working\ActivityTypeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class OutputProcessActivity extends ProcessActivity
{
	/**
	* @inheritdoc
	*/
	protected function matchActivityType(ActivityTypeInterface $activityType)
	{
		parent::matchActivityType($activityType);
		
		if (!$activityType->is('output'))
			throw new \InvalidArgumentException("Output Process Activity must be output type.");
	}
}
