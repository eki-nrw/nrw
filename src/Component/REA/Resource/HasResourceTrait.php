<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Resource;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
trait HasResourceTrait
{
	private $resource;
	
	/**
	* Returns resource
	* 
	* @return ResourceInterface
	*/
	public function getResource()
	{
		return $this->resource;
	}
	
	/**
	* Sets resource
	* 
	* @param ResourceInterface $resource
	* 
	* @return void
	*/
	public function setResource(ResourceInterface $resource = null)
	{
		$this->resource = $resource;
	}
}
