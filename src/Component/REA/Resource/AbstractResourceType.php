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

use Eki\NRW\Mdl\REA\Resource\AbstractResourceType as BaseAbstractResourceType;
use Eki\NRW\Common\Element\HasElementsTrait;
use Eki\NRW\Common\Extension\BuilderInterface;
use Eki\NRW\Common\Extension\BuildInterface;
use Eki\NRW\Common\Extension\NormalizeInterface;
use Eki\NRW\Common\Extension\BaseBuildSubjectTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractResourceType extends BaseAbstractResourceType implements 
	ResourceTypeInterface, BuildInterface, NormalizeInterface
{
	use
		HasElementsTrait,
		BaseBuildSubjectTrait
	;
	
	/**
	* @inheritdoc
	*/
	public function build(BuilderInterface $builder, array $contexts = [])
	{
		//
	}

	/**
	* @inheritdoc
	*/
	public function buildResource(BuilderInterface $builder, array $contexts = [])
	{
		foreach($this->getProperties() as $propName => $propValue)
			$builder->addProperty($propName, $propValue, $contexts);
		foreach($this->getOptions() as $optName => $optValue)
			$builder->addOption($optName, $optValue, $contexts);
		foreach($this->getAttributes() as $attrName => $attrValue)
			$builder->setAttribute($attrName, $attrValue, $contexts);
	}
	
	/**
	* @inheritdoc
	*/
	public function normalize()
	{
		// ...
	}
}
