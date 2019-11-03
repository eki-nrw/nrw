<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Processing\Frame\Process;

use Eki\NRW\Mdl\Processing\Frame\Process\Process as BaseProcess;
use Eki\NRW\Common\Res\Model\ResTrait;
use Eki\NRW\Common\Compose\ObjectStates\ObjectStatesTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Process extends BaseProcess implements ProcessInterface
{
	use
		ResTrait,
		ObjectStatesTrait
	;
}
