<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\Compose\ObjectItem;

use Eki\NRW\Common\Compose\ObjectItem\ComposerInterface as BaseComposerInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ComposerInterface extends BaseComposerInterface
{
	/**
	* Create a new Composer for $obj that has quantity $quantity in specifications $specifications
	* 
	* @param object $obj
	* @param int $quantity
	* @param string $unitAlias
	* @param mixed $link
	* 
	* @return ComposerInterface
	*/
	public static function createFrom($obj, $quantity, $unitAlias, $link = null);
}
