<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Relationship;

use Eki\NRW\Mdl\REA\Relationship\Stockflow as BaseStockflow;
use Eki\NRW\Common\Relations\PotentialTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Stockflow extends BaseStockflow implements RelationshipInterface
{
	use 
		PotentialTrait
	;
	
	const DIRECTION_IN = Constants::STOCKFLOW_IN;
	const DIRECTION_OUT = Constants::STOCKFLOW_OUT;
}
