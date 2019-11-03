<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Plan;

use Eki\NRW\Mdl\Working\Plan\RecipePlanType as BaseRecipePlanType;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class RecipePlanType extends BaseRecipePlanType implements ResInterface
{
	use 
		ResTrait
	;
}
