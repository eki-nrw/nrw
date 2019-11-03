<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Resourcing\Resource\Type;

use Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create new resource type object
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface
	*/
	public function create($identifier);

	/**
	* Load resource type objectby given id
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface
	*/
	public function load($id);

	/**
	* Load resource type object by identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface
	*/
	public function loadByIdentifier($identifier);
	
	/**
	* Delete given resource type
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface $resourceType
	* 
	* @return void
	*/	
	public function delete(ResourceTypeInterface $resourceType);
	
	/**
	* Update a resource type identified
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface $resourceType
	* 
	* @return void
	*/
	public function update(ResourceTypeInterface $resourceType);
}
