<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Relating\Relation;

use Eki\NRW\Common\Relations\RelationInterface as BaseRelationInterface;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Timing\StartEndTimeInterface;
use Eki\NRW\Common\Relations\TypeMeaningInterface;
use Eki\NRW\Common\Relations\PotentialInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface RelationInterface extends 
	BaseRelationInterface,
	ResInterface,
	TypeMeaningInterface,
	PotentialInterface,
	StartEndTimeInterface
{
}
