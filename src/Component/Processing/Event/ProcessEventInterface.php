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

use Eki\NRW\Component\Processing\Frame\HasProcessInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ProcessEventInterface extends 
	EventInterface,
	HasProcessInterface
{
}
