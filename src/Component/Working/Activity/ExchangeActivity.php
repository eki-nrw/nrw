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
use Eki\NRW\Component\Processing\Frame\Exchange\HasExchangeInterface;
use Eki\NRW\Component\Processing\Frame\Exchange\HasExchangeTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeActivity extends Activity implements ExchangeActivityInterface
{
	use
		HasExchangeTrait
	;
	
	/**
	* @inheritdoc
	*/
	protected function matchActivityType(ActivityTypeInterface $activityType)
	{
		parent::matchActivityType($activityType);
		
		if (!$activityType->is('exchange'))
			throw new \InvalidArgumentException("Exchange Activity must be exchange type.");
	}
}
