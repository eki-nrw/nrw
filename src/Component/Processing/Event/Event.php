<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Processing\Event;

use Eki\NRW\Component\REA\Event\AbstractEvent;
use Eki\NRW\Common\Res\Model\ResTrait;
use Eki\NRW\Common\Res\Model\TimestampableTrait;
use Eki\NRW\Common\Compose\ObjectStates\ObjectStatesTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Event extends BaseAbstractEvent implements EventInterface
{
	use 
		ResTrait,
		TimestampableTrait,
		ObjectStatesTrait
	;
}
