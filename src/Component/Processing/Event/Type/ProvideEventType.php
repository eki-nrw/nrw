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

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProvideEventType extends AbstractEventType
{
	public function getEventType()
	{
		return 'event.provide';	
	}
	
	/**
	* @inheritdoc
	*/
	public function isProvide()
	{
		return true;
	}
}
