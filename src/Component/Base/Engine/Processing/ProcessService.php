<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Processing;

use Eki\NRW\Component\Processing\Process\ProcessInterface;

/**
 * Process Service interface.
 */
interface ProcessService extends FrameService
{
	public function createProcess($type);
	
	public function loadProcess($id);
	
	public function update(ProcessInterface $process);
	
	public function delete(ProcessInterface $process);
}
