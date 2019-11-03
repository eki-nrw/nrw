<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Plan\Type;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessRecipePlanType extends RecipePlanType
{
	/**
	* @inheritdoc
	*/
	public function getPlanType()
	{
		return "plan.recipe.process";
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
		if ($thing === 'add_plan_item')
		{
			return parent::accept($thing, $content) and $content->is('process');
		}

		return parent::accept($thing, $content);
	}
}
