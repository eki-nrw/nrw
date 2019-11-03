<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Execution\Type;

use Eki\NRW\Mdl\Working\Execution\Type\ExecutionType as BaseExecutionType;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExecutionType extends BaseExecutionType implements ExecutionTypeInterface
{
	use 
		ResTrait
	;
}
