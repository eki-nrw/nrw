<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Processing\Event\Relationship;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class TransformDuality extends AbstractDuality
{
	public function __construct($subType = '')
	{
		// The agent is "transform" of the other event
		parent::__construct('transform', $subType);
	}
}
