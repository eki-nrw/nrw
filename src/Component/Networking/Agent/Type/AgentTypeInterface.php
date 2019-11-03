<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Agent\Type;

use Eki\NRW\Component\REA\Agent\AgentTypeInterface as BaseAgentTypeInterface;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Common\TypeCheckingInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface AgentTypeInterface extends
	BaseAgentTypeInterface,
	ResInterface,
	TypeCheckingInterface
{
}
