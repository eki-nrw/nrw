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

use Symfony\Component\OptionsResolver\OptionsResolver; 

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ResourceType extends AbstractResourceType
{
	/**
	* @inheritdoc
	*/
	public function getResourceType()
	{
		return 'null';		
	}

	/**
	* @inheritdoc
	*/
    public function configureAttributes(OptionsResolver $resolver)
    {
		$resolver->setDefaults(array(
			'unit_alias' => null,
		));
		$resolver->setAllowedTypes('unit_alias', array('string', 'null'));
	}

	/**
	* @inheritdoc
	*/
	public function getDefaultUnitAlias()
	{
		return $this->getAttribute('unit_alias');
	}
	
	/**
	* Sets default unit alias
	* 
	* @param string $unitAlias
	* 
	* @return void
	*/
	public function setDefaultUnitAlias($unitAlias)
	{
		$this->setAttribute('unit_alias', $unitAlias);
	}
}
