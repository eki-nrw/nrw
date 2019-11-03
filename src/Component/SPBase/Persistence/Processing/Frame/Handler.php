<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Processing\Frame;

use Eki\NRW\Component\Processing\Frame\FrameInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create a new frame of type $identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Processing\Frame\FrameInterface
	* @throws
	*/
	public function create($identifier);
	
	/**
	* Load frame object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Processing\Frame\FrameInterface
	*/
	public function load($id);
	
	/**
	* Delete given frame
	* 
	* @param \Eki\NRW\Component\Processing\Frame\FrameInterface $frame
	* 
	* @return void
	*/	
	public function delete(FrameInterface $frame);
	
	/**
	* Update a frame identified by $id
	* 
	* @param \Eki\NRW\Component\Processing\Frame\FrameInterface $frame
	* 
	* @return void
	*/
	public function update(FrameInterface $frame);
}
