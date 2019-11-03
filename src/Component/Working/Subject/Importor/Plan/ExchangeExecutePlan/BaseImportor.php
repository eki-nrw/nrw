<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\Plan\ExchangeExecutePlan;

use Eki\NRW\Mdl\Working\Subject\AbstractImportor;
use Eki\NRW\Component\Working\Plan\ExchangeExecutePlan;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class BaseImportor extends AbstractImportor 
{
	/**
	* @inheritdoc
	*/
	protected function supportObject($object)
	{
		if ($object instanceof ExchangeExecutePlan)
			return true;
	}
}
