<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Resourcing;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Returns persistence handler of resource
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Resourcing\Resource\Handler
	*/
	public function resourceHandler();

	/**
	* Returns persistence handler of resource type
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Resourcing\Resource\Type\Handler
	*/
	public function resourceTypeHandler();
}
