<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Helper;

use Eki\NRW\Common\Res\Model\ResInterface;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class ResObjectLoader implements ObjectLoaderInterface
{
	/**
	* @inheritdoc
	*/
	public function support($argument)
	{
		return is_object($argument) and $argument instanceof ResInterface;
	}
	
	/**
	* @inheritdoc
	*/
	public function loadObject($argument)
	{
		if ($this->support($argument))
			throw new UnexpectedValueException(sprintf(
				"Parameter 'argument' must be object of %s.",
				ResInterface::class
			));

		return $argument;			
	}
}
