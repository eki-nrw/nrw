<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Working\Execution;

use Eki\NRW\Component\Working\Execution\ExcutionInterface;
use Eki\NRW\Component\REA\Event\HasEventInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ReaExecutionInterface extends
	ExecutionInterface,
	HasEventInterface
{
}
