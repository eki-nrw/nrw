<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Processing\Event;

use Eki\NRW\Component\Processing\Frame\Process\ProcessInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessEvent extends Event implements ProcessEventInterface
{
	/**
	* @var ProcessInterface
	*/
	private $process;

	/**
	* @inheritdoc
	* 
	*/
	public function getProcess()
	{
		return $this->process;		
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function setProcess(ProcessInterface $process = null)
	{
		$this->process = $process;
	}
}
