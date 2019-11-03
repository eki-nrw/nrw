<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\Compose\ObjectItemSource\Composer;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class Method implements MethodInterface
{
	/**
	* Returns method in string
	* 
	* @return string
	*/
	public function __toString()
	{
		return $this->getMethodName();
	}
}
