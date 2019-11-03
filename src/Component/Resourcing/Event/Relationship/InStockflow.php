<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Event\Relationship;

use Eki\NRW\Mdl\REA\Relationship\Stocfkflow as BaseStockflow;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class InStockflow extends BaseStockflow
{
	public function __construct($method = '')
	{
		parent::__construct('in', $method);
	}
}
