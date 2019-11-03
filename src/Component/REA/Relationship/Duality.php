<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Relationship;

use Eki\NRW\Mdl\REA\Relationship\Duality as BaseDuality;
use Eki\NRW\Common\Res\Model\ResTrait;
use Eki\NRW\Common\Timing\StartEndTimeTrait;
use Eki\NRW\Common\Relations\TypeMeaningTrait;
use Eki\NRW\Common\Relations\PotentialTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Duality extends BaseDuality implements RelationshipInterface
{
	use 
		ResTrait,
		StartEndTimeTrait,
		TypeMeaningTrait,
		PotentialTrait
	;
	
}
