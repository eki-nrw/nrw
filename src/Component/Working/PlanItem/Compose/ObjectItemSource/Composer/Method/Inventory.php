<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\Compose\ObjectItemSource\Composer\Method;

use Eki\NRW\Component\Working\PlanItem\Compose\ObjectItemSource\Composer\MethodInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Inventory extends Inside
{
	/**
	* @inheritdoc
	*/
	public static function getMethodName()
	{
		return MethodInterface::METHOD_PRODUCE;	
	}
}
