<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Activity\Type;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessActivityType extends ActivityType
{
	/**
	* @inheritdoc
	*/
	public function getActivityType()
	{
		return "activity.process";
	}
	
	/**
	* @inheritdoc
	*/
	public function is($thing)
	{
		if ($thing === 'process')
			return true;
			
		return parent::is($thing);
	}

	/**
	* @inheritdoc
	*/
	public function accept($thing, $content)
	{
		if ($content instanceof PlanItemTypeInterface and $thing === 'take')
		{
			return $content->is('process');
		}
		if ($content instanceof PlanTypeInterface and $thing === 'take')
		{
			return $content->is('process');
		}
		
		return parent::accept($thing, $content);
	}
}
