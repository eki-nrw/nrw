<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Contexture\Platform;

/**
* Platform configuration interface 
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
interface Configuration
{
	/**
	* Gets the parameter value by name $parameterName
	* 
	* @param string $parameterName
	* @param string|null $use Use case. Default if null.
	* @param mixed|null $defaultValue Default value if cannot get
	* 
	* @return mixed
	*/
	public function getParameter($parameterName, $use = null, $defaultValue = null);
}
