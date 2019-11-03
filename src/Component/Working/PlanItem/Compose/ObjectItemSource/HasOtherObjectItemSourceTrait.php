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
trait HasOtherObjectItemSourceTrait
{
	protected $otherObjectItemSource;
	
	/**
	* @inheritdoc
	*/
	public function getOtherObjectItemSource()
	{
		return $this->otherObjectItemSource;
	}
	
	/**
	* @inheritdoc
	*/	
	public function setOtherObjectItemSource(ObjectItemSourceInterface $objectItemSource)
	{
		$this->otherObjectItemSource = $objectItemSource;
	}
}
