<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject;

use Eki\NRW\Mdl\Working\WorkingSubjectInterface as BaseWorkingSubjectInterface;
use Eki\NRW\Common\Res\Model\ResInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface WorkingSubjectInterface extends 
	BaseWorkingSubjectInterface,
	ResInterface
{
	public function getSubjectId();
}
