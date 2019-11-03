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

use Eki\NRW\Common\Element\AbstractElement;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractResourceTypeElement extends AbstractElement
{
	/**
	* @var ResourceTypeInterface
	*/
	protected $resourceType;
	
	/**
	* @inheritdoc
	*/
	public function getElementType()
	{
		return 'resource_type_element';
	}
	
	public function setResourceType(ResourceTypeInterface $resourceType)
	{
		$this->resourceType = $resourceType;	
	}
	
	public function getContainer()
	{
		return $this->resourceType;
	}
}
