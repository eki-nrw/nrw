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
trait HasProcessTrait
{
	/**
	* @var ProcessInterface
	*/
	private $process;
	
	/**
	* Returns process
	* 
	* @return ProcessInterface
	*/
	public function getProcess()
	{
		return $this->process;
	}

	/**
	* Sets/Resets process
	* 
	* @param ProcessInterface|null $process
	* 
	* @return void
	*/
	public function setProcess(ProcessInterface $process = null)
	{
		$this->process = $process;
	}
}
