<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def\Solution;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class PlanExecuteSolution extends BaseSolution
{
	public function __construct()
	{
		parent::__construct(new PlanExecuteAlgorightm());
	}
	
	/**
	* Returns the identifier of the solution
	* 
	* @return string
	*/
	public function getIdentifier()
	{
		return "working.working_subject.def.solution.plan.execute";
	}
}
