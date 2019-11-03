<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource\Type\Provider;

use Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ProviderInterface
{
	/**
	* Checks if the provider supports $argument
	* 
	* @param mixed $argument
	* 
	* @return bool
	*/
	public function support($argument);
	
	/**
	* Get resource matched condition $argument
	* 
	* @param mixed $argument
	* @param array $contexts
	* 
	* @return ResourceTypeInterface
	*/
	public function get($arguments, array $contexts = []);

	/**
	* Get all resources matched condition $arguments
	* 
	* @param mixed $arguments
	* @param array $contexts
	* @param int $offset
	* @param int $limit
	* 
	* @return ResourceTypeInterface[]
	*/	
	public function getAll($argument, int $offset = 0, int $limit = 25, array $contexts = []);
}
