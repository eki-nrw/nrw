<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\Compose\ObjectItemSource;

use Eki\NRW\Common\Compose\ObjectItemSource\ObjectItemSource;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class Composer implements ComposerInterface
{
	/**
	* 
	* @var string
	* 
	*/
	public $type;
	
	/**
	* 
	* @var object
	* 
	*/
	public $obj;

	/**
	* 
	* @var string
	* 
	*/
	public $method;
	
	/**
	* Constructor
	* 
	* @param string $type
	* @param object $obj
	* @param string $method
	* 
	*/
	public function __construct($type, $obj, $method = null)
	{
		if (empty($this->getAvailableMethods()))
			throw new \BadMethodCallException("getAvailable method cannot return empty array.");
		
		if (null !== $method and !in_array($method, $this->getAvailableMethods()))
			throw new \InvalidArgumentException(sprintf(
				"method '$method' not in suporrted methods of this composer. Available methods are %s",
				implode("|", $this->getAvailableMethods())
			));
			
		if (null === $method)
		{
			$methods = $this->getAvailableMethods();
			$method = $methods[0];
		}
		
		$this->type = $type;
		$this->obj = $obj;
		$this->method = $method;
	}
	
	/**
	* @inheritdoc
	*/
	public static function createFrom($type, $obj, $method)
	{
		return new static($type, $obj, $method);
	}
	
	/**
	* @inheritdoc
	*/
	public function compose()
	{
		return (new ObjectItemSource())
			->setSourceType($this->type)
			->setSourceObject($this->obj)
			->setSourceMethod($this->method)
			->setSourceSpecifications($this->getSpecifications()->__toArray())
		;
	}
}
