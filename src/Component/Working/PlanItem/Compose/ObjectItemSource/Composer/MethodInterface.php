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
interface MethodInterface
{
	const METHOD_INSIDE = 'inside';
	const METHOD_INVENTORY = 'inventory';
	const METHOD_PRODUCE = 'produce';
	const METHOD_OUTSIDE = 'outside';
	const METHOD_PURCHASE = 'purchase';
	const METHOD_CONTRIBUTE = 'contribute';

	/**
	* Returns method name
	* 
	* @return string
	*/	
	public static function getMethodName();
	
	/**
	* Returns method in string
	* 
	* @return string
	*/
	public function __toString();
}
