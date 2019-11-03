<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Event;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractEventTypeExtension implements EventTypeExtensionInterface
{
	/**
	* @inheritdoc
	*/
	public function support($subject, $position = null)
	{
		if ($subject instanceof EventTypeInterface)
			return true;
	}
}
