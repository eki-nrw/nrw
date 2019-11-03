<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Passing;

use Eki\NRW\Component\Processing\FrameInterface;

/**
 * Pass Service interface.
 */
interface FrameService
{
	public function isFrame(FrameInterface $frame);
	
	//public function getFrameType(FrameInterface $frame);

	//public function getTypeOfFrame(FrameInterface $frame);
}
