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
* This interface is designed to manage underlying resources of a resource 
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ResourceWrapperInterface
{
	/**
	* Add an underlying resource
	* 
	* @param ResourceInterface $resource
	* @param string $key
	* 
	* @return void
	* @throws \InvalidArgumentException
	*/
	public function addUnderlyingResource(ResourceInterface $resource, $key = null);
	
	/**
	* Remove the underlying resource that has key
	* 
	* @param string $key
	* 
	* @return void
	* @throws \InvalidArgumentException
	*/
	public function removeUnderlyingResource($key);
	
	/**
	* Get the underlying resource that has key
	* 
	* @param string $key
	* 
	* @return ResourceInterface
	*/
	public function getUnderlyingResource($key);
	
	/**
	* Checks if there is a resource that has key
	* 
	* @param string $key
	* 
	* @return bool
	*/
	public function hasUnderlyingResource($key);
	
	/**
	* Get all underlysing resource
	* 
	* @return array(ResourceInterface)
	*/
	public function getUnderlyingResources();
	
	/**
	* Set all underlying resources
	* 
	* @param array $underlyingResources
	* 
	* @return void
	*/
	public function setUnderlyingResources(array $underlyingResources);
}
