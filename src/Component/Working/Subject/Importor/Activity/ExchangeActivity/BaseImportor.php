<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\Activity\ExchangeActivity;

use Eki\NRW\Component\Working\Subject\Importor\AbstractImportor;
use Eki\NRW\Component\Working\Activity\ExchangeActivityInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class BaseImportor extends AbstractImportor
{
	/**
	* @inheritdoc
	*/
	public function support($data, $object)
	{
		if ($object instanceof ExchangeActivityInterface)
			return true;
	}
}
