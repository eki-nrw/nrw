<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\Type;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessExecutePlanItemType extends ExecutePlanItemType
{
	/**
	* @inheritdoc
	*/
	public function getPlanItemType()
	{
		return "planitem.execute.process";
	}
	
	/**
	* @inheritdoc
	*/
	public function is($thing)
	{
		if ($thing === 'process')
			return true;
			
		return parent::is($thing);
	}
	
	/**
	* @inheritdoc
	*/
	public function accept($thing, $content)
	{
		return parent::accept($thing, $content);
	}

	/**
	* @inheritdoc
	*/
	public function getRoles()
	{
		return array('operator');
	}
}
