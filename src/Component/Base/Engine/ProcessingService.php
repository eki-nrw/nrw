<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is frame to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine;

use Eki\NRW\Component\Base\Engine\Processing\EventService;
use Eki\NRW\Component\Base\Engine\Processing\FrameingService;

/**
 * Processing Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface ProcessingService
{
	/**
	* Returns Event Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\Processing\EventService
	*/
	public function eventService();

	/**
	* Returns Framing Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\Processing\FramingService
	*/
	public function framingService();
}
