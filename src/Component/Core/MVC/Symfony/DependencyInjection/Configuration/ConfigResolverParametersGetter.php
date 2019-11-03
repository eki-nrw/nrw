<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\DependencyInjection\Configuration\ConfigResolver;

use Eki\NRW\Mdl\Contexture\Configuration\ConfigResolver\ParametersGetterInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * 
 */
class ConfigResolverParametersGetter extends ContainerAware implements ParametersGetterInterface
{
	use
		ContainerAwareTrait
	;
	
	/**
	* @inheritdoc
	* 
	*/
	public function hasParameter($paramName)
	{
		return $this->container->hasParameter($paramName);	
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function getParameter($paramName)
	{
		return $this->container->getParameter($paramName);
	}
}
