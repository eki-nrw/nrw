<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\Compose\ObjectItemSource;

use Eki\NRW\Common\Compose\ObjectItemSource\ComposerInterface as BaseComposerInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ComposerInterface extends BaseComposerInterface
{
	/**
	* Create a new Composer for $obj of type $type by method $method in specifications $specifications
	* 
	* @param string $type
	* @param object $obj
	* 
	* @return ComposerInterface
	*/
	public static function createFrom($type, $obj);
	
	/**
	* Returns available methods
	* 
	* @return array(string)
	*/
	public function getAvailableMethods();
	
	/**
	* Get setup specifications
	* 
	* @return
	*/
	public function getSpecifications();
}
