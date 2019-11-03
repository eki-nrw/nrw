<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource\Type;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class CustomResourceType extends ResourceType
{
	/**
	* @property
	* 
	* @var string
	* 
	*/
	public $parentTypeName;
	
	/**
	* @property
	*  
	* @var string
	* 
	*/
	public $typeName;
	
	/**
	* @inheritdoc
	*/
	public function getResourceType()
	{
		if ($this->parentTypeName === null or $this->typeName)
			throw new \LogicException(sprintf("It must determine parent type and this type for %s.", __CLASS__));
			
		return $this->parentTypeName . "." . $this->typeName;		
	}
}
