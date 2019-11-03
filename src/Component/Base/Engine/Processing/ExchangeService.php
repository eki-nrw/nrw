<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Exchangeing;

use Eki\NRW\Component\Exchangeing\Exchange\ExchangeInterface;

/**
 * Exchange Service interface.
 */
interface ExchangeService extends FrameService
{
	public function createExchange($type);
	
	public function loadExchange($id);
	
	public function update(ExchangeInterfae $process);
	
	public function delete(ExchangeInterface $process);
}
