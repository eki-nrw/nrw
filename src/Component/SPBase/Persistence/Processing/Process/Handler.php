<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Processing\Process;

use Eki\NRW\Component\Processing\Process\ProcessInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create a new process of type $identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Processing\Process\ProcessInterface
	* @throws
	*/
	public function create($identifier);
	
	/**
	* Load process object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Processing\Process\ProcessInterface
	*/
	public function load($id);
	
	/**
	* Delete given process
	* 
	* @param \Eki\NRW\Component\Processing\Process\ProcessInterface $process
	* 
	* @return void
	*/	
	public function delete(ProcessInterface $process);
	
	/**
	* Update a process identified by $id
	* 
	* @param \Eki\NRW\Component\Processing\Process\ProcesseInterface $process
	* 
	* @return void
	*/
	public function update(ProcessInterface $process);
}
