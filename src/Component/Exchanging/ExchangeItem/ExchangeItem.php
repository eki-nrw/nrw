<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Exchanging\ExchangeItem;

use Eki\NRW\Mdl\Exchanging\ExchangeItem\AbstractExchangeItem;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeItem extends AbstractExchangeItem implements ExchangeItemInterface
{
	use
		ResTrait
	;

	/**
	* @inheritdoc
	*/
	protected function matchExchangeItemType(ExchangeItemTypeInterface $exchangeItemType)
	{
		parent::matchExchangeItemType($exchangeItemItemType);
	}
}
