<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource\Method\Output;

use Eki\NRW\Component\Resourcing\Resource\Method\AbstractOutput;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Unload extends AbstractOutput
{
	const NAME = 'unload';

	/**
	* @inheritdoc
	* 
	*/
	public function isCreation()
	{
		return false;
	}

	public function getConjunctionInputMethod()
	{
		return "load";
	}
}
