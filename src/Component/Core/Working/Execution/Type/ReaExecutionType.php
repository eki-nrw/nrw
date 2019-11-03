<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Working\Execution\Type;

use Eki\NRW\Common\Working\Execution\Type\ExecutionType;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ReaExecutionType extends ExecutionType
{
	/**
	* @inheritdoc
	*/
	public function getExecutionType()
	{
		return "execution.rea";
	}
	
	/**
	* @inheritdoc
	*/
	public function is($thing)
	{
		if ($thing === 'rea')
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
}
