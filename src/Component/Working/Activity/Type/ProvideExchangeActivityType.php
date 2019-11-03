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
class ProvideExchangeActivityType extends ExchangeActivityType
{
	/**
	* @inheritdoc
	*/
	public function getActivityType()
	{
		return "activity.exchange.provide";
	}
	
	/**
	* @inheritdoc
	*/
	public function is($thing)
	{
		if ($thing === 'provide')
			return true;
			
		return parent::is($thing);
	}

	/**
	* @inheritdoc
	*/
	public function accept($thing, $content)
	{
		return parent::accept($thing, $content);
	}
}
