<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Processing\Pass;

use Eki\NRW\Component\Processing\Pass\PassInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create a new pass of type $identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Processing\Pass\PassInterface
	* @throws
	*/
	//public function create($identifier);
	
	/**
	* Load pass object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Processing\Pass\PassInterface
	*/
	public function load($id);
	
	/**
	* Delete given pass
	* 
	* @param \Eki\NRW\Component\Processing\Pass\PassInterface $pass
	* 
	* @return void
	*/	
	public function delete(PassInterface $pass);
	
	/**
	* Update a pass identified by $id
	* 
	* @param \Eki\NRW\Component\Processing\Pass\PasseInterface $pass
	* 
	* @return void
	*/
	public function update(PassInterface $pass);
}
