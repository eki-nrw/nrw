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
class Registry implements RegistryInterface
{
	protected $methodObjs = [];
	protected $inputMethodObjs = [];
	protected $outputMethodObjs = [];
	
	/**
	* @inheritdoc
	* 
	*/	
	public function getMethod($method)
	{
		if (isset($this->methodObjs[$method]))
			return $this->methodObjs[$method];
	}

	/**
	* @inheritdoc
	* 
	*/	
	public function getInputMethod($method)
	{
		if (isset($this->inputMethodObjs[$method]))
			return $this->inputMethodObjs[$method];
	}

	/**
	* @inheritdoc
	* 
	*/	
	public function getOutputMethod($method)
	{
		if (isset($this->outputMethodObjs[$method]))
			return $this->outputMethodObjs[$method];
	}

	/**
	* @inheritdoc
	* 
	*/	
	public function addMethod(MethodInterface $method)
	{
		if (isset($this->methodObjs[$method->getIdentifier()]))
			throw new \InvalidArgumentException(sprinf(
				"Method with identifier %s already exisits.",
				$method->getIdentifier()
			));
			
		$this->methodObjs[$method->getIdentifier()] = $method;
		
		if ($method->isInput())
			$this->inputMethodObjs[$method->getName()] = $method;
		else
			$this->outputMethodObjs[$method->getName()] = $method;
	}
}
