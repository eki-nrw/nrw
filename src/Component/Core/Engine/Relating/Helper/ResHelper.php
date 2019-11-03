<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Relating\Helper;

use Eki\NRW\Common\Relations\TypeMeaningInterface;
use Eki\NRW\Common\Relations\TypeMeaningHelper;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Factory\Factory;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ResHelper
{
	const INDEX_MIN = TypeMeaningInterface::INDEX_DOMAIN;
	const INDEX_MAX = TypeMeaningInterface::INDEX_SUB_TYPE;
	
	/**
	* Returns the key of the factory entry
	* 
	* @param string $identifier
	* @param string|array $type
	* 	If array:
	* 		0 => <domain>
	* 		1 => <categorization>
	* 		2 => <main type>
	* 		3 => <sub type>
	* 
	* @return string
	*/
	static public function factoryType($identifier, $type)
	{
		if (empty($type))
		{
			return $identifier;
		}
		else
		{
			$meaningTypes = self::meaningTypes($type);	
			$fType = $identifier;
			if (!empty($meaningTypes[TypeMeaningInterface::INDEX_DOMAIN]))
			{
				$fType .= ":" . $meaningTypes[TypeMeaningInterface::INDEX_DOMAIN];
				if (!empty($meaningTypes[TypeMeaningInterface::INDEX_CATEGORIZATION_TYPE]))
				{
					$fType .= 
						TypeMeaningInterface::RELATION_TYPE_CHAIN_DELIMETER . 
						$meaningTypes[TypeMeaningInterface::INDEX_CATEGORIZATION_TYPE]
					;
				}
			}

			return $fType;			
		}
	}

	/**
	* Returns meaning types from the given string/array type
	* 
	* @param string|array $type
	* 
	* @return array
	*/	
	static public function meaningTypes($type)
	{
		if (!empty($type))
		{
			if (is_string($type))
			{
				$typeChain = (new TypeMeaningHelper())->setType($type)->getTypeChain();
				$mTypes = [];
				foreach($typeChain as $k => $t)
				{
					if (empty($t))
						break;
					$mTypes[$k] = $t;
				}
				
				return $mTypes;
			}
			else if (is_array($type))
			{
				$mTypes = [];
				for($i=self::INDEX_MIN;$i<=self::INDEX_MAX;$i++)
				{
					// consecutive indices
					if (!isset($type[$i]) or empty($type[$i]))
						break;
						
					$mTypes[$i] = $type[$i];
				}

				return $mTypes;
			}
			else
			{
				throw new \InvalidArgumentException("Parameter 'type' must be string or array.");
			}
		}
		else
		{
			return array();
		}
	}

	/**
	* Returns the type of the relation object
	* 
	* @param string|array $type
	* 
	* @return string
	*/
	static public function relationType($type)
	{
		if ($type === null)
		{
			return $type;
		}
		else
		{
			if (is_string($type))
			{
				return $type;
			}
			else if (is_array($type))
			{
				$str = "";
				for($i=self::INDEX_MIN;$i<=self::INDEX_MAX;$i++)
				{
					if (!isset($type[$i]) or empty($type[$i]))
						break;
						
					$str .= 
						($i !== self::INDEX_MIN ? TypeMeaningInterface::RELATION_TYPE_CHAIN_DELIMETER : "") .
						$type[$i]
					;
				}
				
				return $str;
			}
			else
			{
				throw new \InvalidArgumentException("Parameter 'type' must be string or array.");
			}
		}
	}

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
	static public function createMetadata(array $inputs)
	{
		$classes = [];
		foreach($inputs as $input)
		{
			$classes[static::factoryType($input['identifier'], $input['type'])] = $input['class'];
		}
		
		return new Metadata(
			'relating', 
			$classes,
			array(
				'cache_prefix' => 'relating',
				'cache_tag' => 'relating-tag'
			)
		);	
	}
}
