<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Exchanging\Exchange\Type;

use Eki\NRW\Mdl\Exchanging\Exchange\Type\AbstractExchangeType;
use Eki\NRW\Common\Res\Model\ResTrait;

use Symfony\Component\OptionResolver\OptionResolver;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExchangeType extends AbstractExchangeType implements ExchangeTypeInterface
{
	use
		ResTrait
	;
	
	/**
	* @inheritdoc
	*/
	public function	getExchangeType()
	{
		return 'exchange';
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
	
	public function configureTiming(OptionResolver $resolver)
	{
		//...	
	}
}
