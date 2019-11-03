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

use Eki\NRW\Common\Extension\AbstractBuilder;
use Eki\NRW\Common\Extension\CreateBuilderInterface;
use Eki\NRW\Common\Res\Factory\FactoryInterface;
use Eki\NRW\Common\QuantityValue\QuantityValueInterface;
use Eki\NRW\Common\QuantityValue\QuantityValue;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ResourceBuilder extends AbstractBuilder implements CreateBuilderInterface
{
	/**
	* @var ResourceTypeInterface
	*/
	protected $resourceType;
	
	/**
	* @inheritdoc
	*/
	public function __construct(ResourceTypeInterface $resourceType, FactoryInterface $factory, array $extensions = [])
	{
		$this->resourceType = $resourceType;
		
		parent::__construct($resourceType->getResourceType(), $factory, $extensions);		
	}

	/**
	* @inheritdoc
	*/	
	protected function initialize($object)
	{
		parent::initialize($object);
		
		$object->setResourceType($this->resourceType);
	}

	/**
	* @inheritdoc
	*/	
	protected function buildFromDependencyInjection($object)
	{
		$resource = $object;
		
		if (!empty($this->resourceType->getDefaultUnitAlias()))
		{
			$resource->setQuantityValue(new QuantityValue(0, $this->resourceType->getDefaultUnitAlias()));
		}
		
		$this->resourceType->buildResource($this, array('resource_object' => $object, 'position' => 'build'));
	}

	/**
	* @inheritdoc
	*/
	public function createBuilder($type)
	{
		if (!$type instanceof ResourceTypeInterface)
			throw new \InvalidArgumentException("type parameter must be instance ResourceTypeInterface.");
		
		$thisClass = get_class($this);
		
		return new $thisClass($type, $this->getFactory(), array_values($this->getExtensions()));
	}	
}
