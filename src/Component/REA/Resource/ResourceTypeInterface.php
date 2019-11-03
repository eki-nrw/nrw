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

use Eki\NRW\Mdl\REA\Resource\ResourceTypeInterface as BaseResourceTypeInterface;
use Eki\NRW\Common\Element\HasElementsInterface;
use Eki\NRW\Common\Extension\BuilderInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ResourceTypeInterface extends
	BaseResourceTypeInterface,
	HasElementsInterface
{
	/**
	* Build and returns resource
	* The opportunity to modify build information. It is called when the resource built
	* 
	* @param BuilderInterface $builder
	* @param array $contexts
	* 
	* @return void
	*/
	public function buildResource(BuilderInterface $builder, array $contexts = []);

	// Management Features:
	//	
	// + Supported REA input methods
	// + Supported REA output methods
}
