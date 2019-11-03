<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Exchanging\ExchangeItem\Type;

use Eki\NRW\Mdl\Exchanging\ExchangeItem\Type\AbstractExchangeItemType;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeItemType extends AbstractExchangeItemType implements ExchangeItemTypeInterface
{
	use
		ResTrait
	;
	
	/**
	* @inheritdoc
	*/
	public function	getExchangeItemType()
	{
		return 'exchangeitem';
	}
	
	/**
	* @inheritdoc
	*/
	public function is($thing)
	{
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
