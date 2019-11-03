<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine;

use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;
use Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface;

/**
 * Resourcing Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface ResourcingService
{
	/**
	* Create a new resource type by resource type identifier
	* 
	* @param string $identifier 
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface
	*/
	public function createResourceType($identifier);

	/**
	* Load resource type by id
	* 
	* @param mixed $resourceTypeId
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface
	*/
	public function loadResourceType($resourceTypeId);
	
	/**
	* Load a reousrce type by identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface
	*/
	public function loadResourceTypeByIdentifier($identifier);

	/**
	* Delete a reousrce type
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface $resourceType
	* 
	* @return void
	*/	
	public function deleteResourceType(ResourceTypeInterface $resourceType);
	
	/**
	* Update a resource type
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface $resourceType
	* 
	* @return void
	*/
	public function updateResourceType(ResourceTypeInterface $resourceType);

	/**
	* Get all faceting categorizations of resource type
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface $resourceType
	* 
	* @return \Eki\NRW\Component\Faceting\Facet\FacetValueInterface[]
	*/
	public function getResourceTypeCategorization(ResourceTypeInterface $resourceType);
	
	/**
	* Create new resource of a resource type $resourceTypeId
	* 
	* @param mixed $resourceTypeId
	* 
	* @return ResourceInterface
	*/
	public function createResource($resourceTypeId);
	
	/**
	* Load a resource by id
	* 
	* @param mixed $resourceId
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\ResourceInterface
	*/
	public function loadResource($resourceId);
	
	/**
	* Delete a resource
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\ResourceInterface $resource
	* 
	* @return void
	*/
	public function deleteResource(ResourceInterface $resource);
	
	/**
	* Update a resouece entity to persistence storage 
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\ResourceInterface $resource
	* 
	* @return void
	*/
	public function updateResource(ResourceInterface $resource);
	
	/**
	* Make a linkage between two resources
	* 
	* @param ResourceInterface $resource
	* @param ResourceInterface $otherResource
	* @param string $linkageType
	* 
	* $linkageType:
	* + 'underlying'
	* + 'container'
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Relationship\LinkageInterface
	*/
	public function linkResources(ResourceInterface $resource, ResourceInterface $otherResource, $linkageType);
	
	/**
	* Remove the linkage between two resources
	* 
	* @param ResourceInterface $resource
	* @param ResourceInterface $otherResource
	* @param string|null $linkageType
	* 
	* @return void
	*/
//	public function unlinkResources(ResourceInterface $resource, ResourceInterface $otherResource, $linkageType = null);
	
	/**
	* Gets all linkages between two resources
	* 
	* @param ResourceInterface $resource
	* @param ResourceInterface $otherResource
	* @param string $linkageType
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Relationship\LinkageInterface
	*/
	public function getLinkage(ResourceInterface $resource, ResourceInterface $otherResource, $linkageType);

	/**
	* Gets all linkages between two resources
	* 
	* @param ResourceInterface $resource
	* @param string|null $linkageType Null if any linkage type
	* @param ResourceInterface|null $otherResource
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Relationship\LinkageInterface[]
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\UnauthorizedException
	*/
	public function getLinkages(ResourceInterface $resource, $linkageType = null, ResourceInterface $otherResource = null);
}
