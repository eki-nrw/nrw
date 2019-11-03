<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource\Method;

use Eki\NRW\Component\REA\Resource\Method\MethodInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface RegistryInterface
{
	/**
	* Returns method registred object
	* 
	* @param string $method Method identifier
	* 
	* @return MethodInterface|null
	*/
	public function getMethod($method);

	/**
	* Returns input method registered object
	* 
	* @param string $method Method name
	* 
	* @return MethodInterface|null
	*/
	public function getInputMethod($method);

	/**
	* Returns output method registred object
	* 
	* @param string $method Method name
	* 
	* @return MethodInterface|null
	*/
	public function getOutputMethod($method);
	
	/**
	* Register a new @method
	* 
	* @param MethodInterface $method
	* 
	* @return void
	* 
	* @throws
	*/
	public function addMethod(MethodInterface $method);
}
