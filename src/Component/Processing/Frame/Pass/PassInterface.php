<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Processing\Frame\Pass;

use Eki\NRW\Mdl\Processing\Frame\Pass\PassInterface as BasePassInterface;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Compose\ObjectStates\ObjectStatesInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface PassInterface extends
	BasePassInterface,
	ResInterface,
	ObjectStatesInterface
{
}
