<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Callback\Plan;

use Eki\NRW\NRW\Base\Exchange\Exchange\ExchangeTypeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeRecipePlanCallback extends RecipePlanCallback
{
	/**
	* @inheritdoc
	*/
	public function getCallbackType()
	{
		return 'plan.recipe.exchange';
	}

	protected function addExchangeType($type, $data)
	{
		$this->getPlan()->setSolution($data);
	}

	protected function addExchangeTypeSupport($type, $data)
	{
		return $data instanceof ExchangeTypeInterface;
	}
}
