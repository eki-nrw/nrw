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
trait ResourceWrapperTrait
{
	protected $underlyingResources = [];
	
	/**
	* @inheritdoc
	*/
	public function addUnderlyingResource(ResourceInterface $resource, $key = null)
	{
		if (null === $key)
			$key = $resource->getResourceName();
			
		if (isset($this->underlyingResources[$key]))
			throw new \InvalidArgumentException(sprintf('Underlying Resource with key %s already exists.', $key));
			
		$this->underlyingResources[$key] = $resource;
	}
	
	/**
	* @inheritdoc
	*/
	public function removeUnderlyingResource($key)
	{
		if (!isset($this->underlyingResources[$key]))
			throw new \InvalidArgumentException(sprintf('No Resource with key %s to remove.', $key));
			
		unset($this->underlyingResources[$key]);
	}
	
	/**
	* @inheritdoc
	*/
	public function getUnderlyingResource($key)
	{
		if (!isset($this->underlyingResources[$key]))
			throw new \InvalidArgumentException(sprintf('No Resource with key %s to get.', $key));
			
		return $this->underlyingResources[$key];
	}
	
	/**
	* @inheritdoc
	*/
	public function hasUnderlyingResource($key)
	{
		return isset($this->underlyingResources[$key];
	}
	
	/**
	* @inheritdoc
	*/
	public function getUnderlyingResources()
	{
		return $this->underlyingResources;	
	}
	
	/**
	* @inheritdoc
	*/
	public function setUnderlyingResources(array $underlyingResources)
	{
		$this->underlyingResources = [];
		foreach($underlyingResources as $key => $resource)
		{
			$this->addUnderlyingResource($resource, $key);
		}
	}
}
