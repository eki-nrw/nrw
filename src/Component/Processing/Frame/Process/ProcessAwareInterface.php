<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Processing\Frame\Process;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ProcessAwareInterface
{
	/**
	* Sets/Resets process
	* 
	* @param ProcessInterface|null $process
	* 
	* @return void
	*/
	public function setProcess(ProcessInterface $process = null);
}
