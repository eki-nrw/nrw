<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\Security\Authorization;

/**
 * 
 */
class Attribute
{
	/**
	* 
	* @var string
	* 
	*/
	public $service;
	
	/**
	* 
	* @var string
	* 
	*/
	public $permission;
	
	/**
	* 
	* @var array
	* 
	*/
	public $limitations;
	
	public function __construct($service, $permission, $limitations = array())
	{
		$this->service = $service;
		$this->permission = $permission;
		$this->limitations = $limitations;
	}
	
	public function __toString()
	{
		return "ROLE_{$this->service}_{$this->permission}";
	}
}
