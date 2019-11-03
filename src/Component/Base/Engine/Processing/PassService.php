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

use Eki\NRW\Component\Processing\Pass\PassInterface;

/**
 * Pass Service interface.
 */
interface PassService extends FrameService
{
	public function createPass($type);
	
	public function loadPass($id);
	
	public function update(PassInterfae $process);
	
	public function delete(PassInterface $process);
}
