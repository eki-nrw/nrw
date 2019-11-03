<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\PlanItem\Compose\ObjectItem;

use Eki\NRW\Common\Compose\ObjectItem\ObjectItem;
use Eki\NRW\Common\QuantityValue\QuantityValue;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class Composer implements ComposerInterface
{
	/**
	* 
	* @var object
	* 
	*/
	public $obj;

	/**
	* 
	* @var int
	* 
	*/
	public $quantity;
	
	/**
	* 
	* @var string
	* 
	*/
	public $unitAlias;
	
	/**
	* 
	* @var array
	* 
	*/
	public $specifications = [];
	 
	/**
	* 
	* @var mixed
	* 
	*/
	public $link;
	
	/**
	* Constructor
	* 
	* @param object $obj
	* @param int $quantity
	* @param string $unitAlias
	* @param array $specifications
	* @param mixed $link
	* 
	*/
	public function __construct($obj, $quantity = 0, $unitAlias = '', array $specifications = [], $link = null)
	{
		$this->obj = $obj;
		$this->quantity = $quantity;
		$this->unitAlias = $unitAlias;
		$this->specifications = $specifications;
		$this->link = $link;
	}
	
	/**
	* @inheritdoc
	*/
	public static function createFrom($obj, $quantity, $unitAlias, $link = null)
	{
		return new static($obj, $quantity, $unitAlias, $link);
	}
	
	/**
	* @inheritdoc
	*/
	public function compose()
	{
		return (new ObjectItem())
			->setItem($this->obj)
			->setQuantityValue(
				(new QuantityValue())
					->setQuantity($this->quantity)
					->setUnitAlias($this->unitAlias)
			)
			->setSpecifications($this->specifications)
			->setLink($this->link)
		;
	}
}
