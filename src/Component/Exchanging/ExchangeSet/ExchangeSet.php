<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Exchanging;

use Eki\NRW\Common\Exchanging\AbstractExchangeSet;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeSet extends AbstractExchangeSet implements ExchangeSetInterface
{
	use
		ResTrait
	;

	/**
	* @inheritdoc
	*/
	protected function matchExchangeSetType(ExchangeSetTypeInterface $exchangeSetType)
	{
		parent::matchExchangeSetType($exchangeSetType);
	}
}
