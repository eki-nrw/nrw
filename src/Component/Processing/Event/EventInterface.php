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

use Eki\NRW\Component\REA\Event\EventInterface as ReaEventInterface;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Res\Model\TimestampableInterface;
use Eki\NRW\Common\Compose\ObjectStates\ObjectStatesInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface EventInterface extends 
	ReaEventInterface,
	ResInterface,
	TimestampableInterface,
	ObjectStatesInterface
{
}
