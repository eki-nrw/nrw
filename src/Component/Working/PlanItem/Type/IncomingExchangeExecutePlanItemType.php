<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\Type;

use Eki\NRW\Mdl\Working\PlanItem\Type\ExecutePlanItemType;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class IncomingExchangeExecutePlanItemType extends ExchangeExecutePlanItemType
{
	/**
	* @inheritdoc
	*/
	public function getPlanItemType()
	{
		return "planitem.execute.exchange.incoming";
	}
	
	/**
	* @inheritdoc
	*/
	public function is($thing)
	{
		if ($thing === 'incoming')
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
