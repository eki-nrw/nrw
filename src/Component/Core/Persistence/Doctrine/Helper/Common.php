<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Helper;

use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Factory\Factory;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
final class Common
{
	/**
	* Create the relations factory from formetted inputs
	* 
	* @param array $inputs
	*     'identifier' => <relation type of relation class>
	*     'type'	   => <type of relation>
	*     'class'      => <classname of relation>
	* 
	* @return \Eki\NRW\Common\Res\Factory\Factory
	*/
	static public function createFactory(array $inputs)
	{
		$registries = [];
		foreach($inputs as $input)
		{
			$registries[static::factoryType($input['identifier'], $input['type'])] = $input['class'];
		}
		
		return new Factory($registries);	
	}

	/**
	* Create the valid metadata for relation service
	* 
	* @param array $inputs
	*     'identifier' => <relation type of relation class>
	*     'type'	   => <type of relation>
	*     'class'      => <classname of relation>
	* 
	* @return
	*/
	static public function createMetadata($alias, array $inputs, $prefix = null)
	{
		$classes = [];
		foreach($inputs as $input)
		{
			$classes[static::factoryType($input['identifier'], $input['type'])] = $input['class'];
		}
		
		$prefix = $prefix === null ? $alias : $prefix;
		return new Metadata(
			$alias, 
			$classes,
			array(
				'cache_prefix' => $prefix,
				'cache_tag' => $prefix . '-tag'
			)
		);	
	}
}
