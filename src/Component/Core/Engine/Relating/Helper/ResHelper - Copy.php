<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Relation\Helper;

use Eki\NRW\Common\Relations\TypeMeaningInterface;
use Eki\NRW\Common\Relations\TypeMeaningHelper;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Factory\Factory;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ResHelper
{
	/**
	* Returns the key of the factory entry
	* 
	* @param string $identifier
	* @param string|array $type
	* 
	* @return
	*/
	static public function factoryType($identifier, $type)
	{
		if ($type === null)
		{
			return $identifier;
		}
		else
		{
			if (is_string($type))
			{
				$meaningTypes = self::meaningTypes($type);	
			}
			else if (is_array($type))
			{
				$meaningTypes = $type;			
			}
			else
			{
				throw new \InvalidArgumentException("Parameter 'type' must be string or array.");
			}
			
			$fType = $identifier;
			if (!empty($meaningTypes[TypeMeaningInterface::INDEX_DOMAIN]))
			{
				$fType .= ":" . $meaningTypes[TypeMeaningInterface::INDEX_DOMAIN];
				if (!empty($meaningTypes[TypeMeaningInterface::INDEX_CATEGORIZATION_TYPE]))
				{
					$fType .= "-"	. $meaningTypes[TypeMeaningInterface::INDEX_CATEGORIZATION_TYPE];
				}
			}

			echo 
				"factoryType(" .
				$identifier .
				", " . 
				($type === null ? "null" : $type) .
				")=" . 
				$fType . 
				"\n"
			;
				
			return $fType;			
		}
	}

	/**
	* Returns meaning types from the given string type
	* 
	* @param string $type
	* 
	* @return array
	*/	
	static public function meaningTypes($type)
	{
		if ($type !== null)
		{
			if (is_string($type))
			{
				$typeHelper = new TypeMeaningHelper($type);
				$argsTypes = array(
					TypeMeaningInterface::INDEX_DOMAIN => $typeHelper->getRelationDomain(),
					TypeMeaningInterface::INDEX_CATEGORIZATION_TYPE => $typeHelper->getCategorizationType(),
					TypeMeaningInterface::INDEX_MAIN_TYPE => $typeHelper->getMainType(),
					TypeMeaningInterface::INDEX_SUB_TYPE => $typeHelper->getSubType()
				);
				
				return $argsTypes;
			}
			else if (is_array($type))
			{
				return $type;		
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
	* Create the relations factory from formatted inputs
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
			$registries[Helper::factoryType($input['identifier'], $input['type'])] = $input['class'];
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
			$classes[Helper::factoryType($input['identifier'], $input['type'])] = $input['class'];
		}
		foreach($inputs as $input)
		{
			$classes['default'] = $input['class'];
			break;
		}
		
		return new Metadata(
			'relation', 
			$classes,
			array(
				'cache_prefix' => 'relation',
				'cache_tag' => 'relation-tag'
			)
		);	
	}
}
