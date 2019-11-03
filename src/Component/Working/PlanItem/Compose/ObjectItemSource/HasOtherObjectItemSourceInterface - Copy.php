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

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface HasOtherObjectItemSourceInterface
{
	/**
	* Gets the object item source (other)
	* 
	* @return ObjectItemSourceInterface
	*/
	public function getOtherObjectItemSource();
	
	/**
	* Sets the object item source (other)
	* 
	* @param ObjectItemSourceInterface $objectItemSource
	* 
	* @return void
	*/
	public function setOtherObjectItemSource(ObjectItemSourceInterface $objectItemSource);
}
