<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource\Method;

use Eki\NRW\Component\REA\Resource\Method\AbstractOutputMethod;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractOutput extends AbstractOutputMethod
{
	const TYPE = 'output';
	const NAME = '';
	
	/**
	* @inheritdoc
	* 
	*/
	public function getIdentifier()
	{
		return static::TYPE.".".self::NAME;
	}

	public function getConjunctionInputMethod()
	{
	}
}
