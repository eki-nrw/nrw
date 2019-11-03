<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Processing\Event\Type;

use Eki\NRW\Component\REA\Event\AbstractEventType as BaseAbstractEventType;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractEventType extends BaseAbstractEventType implements EventTypeInterface
{
	use
		ResTrait
	;
}
