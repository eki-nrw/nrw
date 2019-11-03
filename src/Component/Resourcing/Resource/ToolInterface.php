<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource;

use Eki\NRW\Component\Resourcing\Resource\Type\Creator\RegistryInterface;
use Eki\NRW\Component\Resourcing\Resource\Type\Provider\RegistryInterface;
use Eki\NRW\Component\Resourcing\Resource\Creator\RegistryInterface;
use Eki\NRW\Component\Resourcing\Resource\Provider\RegistryInterface;
use Eki\NRW\Component\Resourcing\Resource\Method\RegistryInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ToolInterface
{
	/**
	* Returns ResourceTypeCreator Registry
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Type\Creator\RegistryInterface
	*/
	public function getResourceTypeCreatorRegistry();

	/**
	* Returns ResourceTypeProvider Registry
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Type\Provider\RegistryInterface
	*/
	public function getResourceTypeProviderRegistry();

	/**
	* Returns ResourceCreator Registry
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Creator\RegistryInterface
	*/
	public function getResourceCreatorRegistry();

	/**
	* Returns ResourceProvider Registry
	* 
	* @return \Eki\NRW\Component\Resourcing\Resource\Provider\RegistryInterface
	*/
	public function getResourceProviderRegistry();

	/**
	* Returns Method Registry
	* 
	* @return \Eki\NRW\Component\Resoucing\Resource\Method\RegistryInterface
	*/
	public function getMethodRegistry();
}
