<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Plan;

use Eki\NRW\Mdl\Working\Plan\ExecutePlan as BaseExecutePlan;
use Eki\NRW\Common\Res\Model\ResTrait;
use Eki\NRW\Component\Processing\Solution\ContextInterface as SolutionContext;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExecutePlan extends BaseExecutePlan implements PlanInterface
{
	use 
		ResTrait
	;

	/**
	* @inheritdoc
	*/
	public function setSolution($solution)
	{
		if ($solution != null and !$solution instanceof SolutionContext)
			throw new InvalidArgumentException(sprintf(
				'Solution must be instance of %s',
				SolutionContext::class
			));
		
		parent::setSolution($solution);		
	}
}
