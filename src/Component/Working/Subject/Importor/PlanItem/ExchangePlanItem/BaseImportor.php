<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\PlanItem\ExchangePlanItem;

use Eki\NRW\Mdl\Working\Subject\AbstractImportor;
use Eki\NRW\Component\Working\PlanItem\ExchangeExecutePlanItem;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class BaseImportor extends AbstractImportor
{
	/**
	* Checks if it can be imported
	* 
	* @param mixed $data
	* @param object $object
	* 
	* @return bool
	*/
	public function support($data, $object)
	{
		if ($object instanceof ExchangeExecutePlanItem)
			return true;
	}
}
