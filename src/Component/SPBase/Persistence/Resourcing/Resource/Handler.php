<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Resourcing\Resource;

use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;

use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException;


/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
	/**
	* Create new resource object
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\ResourceInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	*/
	public function create($identifier);
	
	/**
	* Load resource object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\ResourceInterface
	* 
	* @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException
	* @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException
	* 
	*/
	public function load($id);
	
	/**
	* Delete given resource
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\ResourceInterface $resource
	* 
	* @return void
	*/	
	public function delete(ResourceInterface $resource);
	
	/**
	* Update a resource identified by $id
	* 
	* @param \Eki\NRW\Component\Resourcing\Resource\ResourceeInterface $resource
	* 
	* @return void
	*/
	public function update(ResourceInterface $resource);
}
