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
class ProvideExchangeActivity extends ExchangeActivity
{
	/**
	* @inheritdoc
	*/
	protected function matchActivityType(ActivityTypeInterface $activityType)
	{
		parent::matchActivityType($activityType);
		
		if (!$activityType->is('provide'))
			throw new \InvalidArgumentException("Provide Exchange Activity must be provide type.");
	}
}
