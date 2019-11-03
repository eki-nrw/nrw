<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Relating;

use Eki\NRW\Component\Core\Persistence\Doctrine\Relating\Helper;
use Eki\NRW\Common\Res\Factory\Factory;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Relations\TypeMeaningInterface;

use PHPUnit\Framework\TestCase;

use \stdClass;

class HelperTest extends TestCase
{
	const DELIM = TypeMeaningInterface::RELATION_TYPE_CHAIN_DELIMETER;
	const DOMAIN = TypeMeaningInterface::INDEX_DOMAIN;
	const CAT = TypeMeaningInterface::INDEX_CATEGORIZATION_TYPE;
	const MAIN = TypeMeaningInterface::INDEX_MAIN_TYPE;
	const SUB = TypeMeaningInterface::INDEX_SUB_TYPE;
	
	public function testFactoryTypeIdentifierOnly()
	{
		$identifier = 'relation';
		$this->assertSame($identifier, Helper::factoryType($identifier, null));		
	}

	public function testFactoryTypeIdentifierAndDomain()
	{
		$identifier = 'relation';
		$this->assertSame(
			$identifier.":dom", 
			Helper::factoryType($identifier, array(self::DOMAIN => 'dom'))
		);		
	}

	public function testFactoryTypeIdentifierAndDomainAndCategorization()
	{
		$identifier = 'relation';
		$this->assertSame(
			$identifier.":dom".self::DELIM."cat", 
			Helper::factoryType($identifier, array(
				self::DOMAIN => 'dom',
				self::CAT => 'cat'
			)
		));		
	}

	/**
	* @dataProvider getFactoryTypeWithTypeIsString
	* 
	*/
	public function testFactoryTypeWithTypeIsString($identifier, $type)
	{
		$fType = Helper::factoryType($identifier, $type);
		echo 'factoryType(' . $identifier . ', ' . $type . ')=' . $fType . "\n";
	}

	public function testRelationTypeWithTypeIsString()
	{
		$identifier = "relationship";
		$type = "rea|stockflow|in";
		$relationType = Helper::relationType($type);
		
		$this->assertSame($type, $relationType);
	}
	
	/**
	* @dataProvider getFactoryTypeWithTypeIsString
	* 
	*/
	public function testRelationTypeWithTypeIsStringWithDataProvider($identifier, $type)
	{
		$relationType = Helper::relationType($type);
		echo 'relationType(' . $identifier . ', ' . $type . ')=' . $relationType . "\n";
	}

	/**
	* @dataProvider getFactoryTypeWithTypeIsArray
	* 
	*/
	public function testFactoryTypeWithTypeIsArray($identifier, $type)
	{
		$fType = Helper::factoryType($identifier, $type);
		
		$str = "factoryType of ";
		for($i=self::DOMAIN;$i<sizeof($type);$i++)
		{
			$str .= ($i!==self::DOMAIN ? self::DELIM : "") . $type[$i];
		}
		$str .=	" is " . $fType . "\n";
		echo $str;
	}

	/**
	* @dataProvider getFactoryTypeWithTypeIsArray
	* 
	*/
	public function testRelationTypeWithTypeIsArray($identifier, $type)
	{
		$relationType = Helper::relationType($type);
		
		$str = "relation type of ";
		for($i=self::DOMAIN;$i<sizeof($type);$i++)
		{
			$str .= ($i!==self::DOMAIN ? self::DELIM : "") . $type[$i];
		}
		$str .=	" is " . $relationType . "\n";
		echo $str;
	}

	/**
	* @expectedException \InvalidArgumentException
	* 
	*/
	public function testFactoryTypeWithTypeIsNotStringNotArray()
	{
		$fType = Helper::factoryType('an_identifier', new stdClass);
	}
	
	/**
	* @dataProvider getMeaningTypeData
	* 
	*/
	public function testMeaningType($type)
	{
		$mTypes = Helper::meaningTypes($type);
		
		$str = 'meaning type of ';
		if (is_string($type))
			$str .= 'string ' . $type . ' is ';
		else
		{
			$str .= "array [";
			for($i=self::DOMAIN;$i<sizeof($type);$i++)
			{
				$str .= ($i!==self::DOMAIN ? ", " : "") . $i . "=>" . $type[$i];	
			}
			$str .= "] is ";
		}

		$str .= "[";
		$keyInStrings = array(
			self::DOMAIN => 'dom',
			self::CAT => 'cat',
			self::MAIN => 'main',
			self::SUB => 'sub'
		);
		for($i=self::DOMAIN;$i<sizeof($mTypes);$i++)
		{
			$str .= ($i!==self::DOMAIN ? ", " : "") . $keyInStrings[$i] . "=>" . $mTypes[$i];	
		}
		$str .= "]";
		$str .= "\n";
		
		echo $str;
	}
	
	/**
	* @dataProvider getInputs
	* 
	*/
	public function testCreateFactory(array $inputs)
	{
		$factory = Helper::createFactory($inputs);
		$this->assertNotNull($factory);
		$this->assertInstanceOf(Factory::class, $factory);
	}

	/**
	* @dataProvider getInputs
	* 
	*/
	public function testCreateMetadata(array $inputs)
	{
		$metadata = Helper::createMetadata($inputs);
		$this->assertNotNull($metadata);
		$this->assertInstanceOf(Metadata::class, $metadata);
	}
	
	public function getInputs()
	{
		return [
			[
				[
					[
						'identifier' => 'relation',
						'type' => null,
						'class' => stdClass::class
					],
				]
			],

			[
				[
					[
						'identifier' => 'relationship',
						'type' => null,
						'class' => stdClass::class
					],
					[
						'identifier' => 'relationship',
						'type' => 'rea'.self::DELIM.'association',
						'class' => stdClass::class
					],
					[
						'identifier' => 'relationship',
						'type' => 'rea'.self::DELIM.'participation',
						'class' => stdClass::class
					],
					[
						'identifier' => 'relationship',
						'type' => 'rea'.self::DELIM.'custody',
						'class' => stdClass::class
					],
					[
						'identifier' => 'relationship',
						'type' => 'rea'.self::DELIM.'duality',
						'class' => stdClass::class
					],
					[
						'identifier' => 'relationship',
						'type' => 'rea'.self::DELIM.'stockflow',
						'class' => stdClass::class
					],				[
						'identifier' => 'relationship',
						'type' => 'rea'.self::DELIM.'linkage',
						'class' => stdClass::class
					],
				],
			]
		];		
	}

	public function getMeaningTypeData()
	{
		return [
			[ null ],

			[ 'rea'.self::DELIM.'association' ],
			[ 'rea'.self::DELIM.'association'.self::DELIM."child" ],
			[ 'rea'.self::DELIM.'participation' ],
			[ 'rea'.self::DELIM.'custody' ],
			[ 'rea'.self::DELIM.'duality' ],
			[ 'rea'.self::DELIM.'stockflow' ],
			[ 'rea'.self::DELIM.'stockflow'.self::DELIM.'in' ],
			[ 'rea'.self::DELIM.'linkage' ],

			[ 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'association' 
				]
			],
			[ 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'association',
					self::MAIN => 'member'
				]
			],
			[ 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'participation' 
				]
			],
			[ 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'custody' 
				]
			],
			[ 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'duality' 
				]
			],
			[ 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'stockflow' 
				]
			],
			[ 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'stockflow',
					self::MAIN => 'in'
				]
			],
			[ 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'stockflow',
					self::MAIN => 'out'
				]
			],
			[ 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'linkage' 
				]
			],
		];
	}

	public function getFactoryTypeWithTypeIsString()
	{
		return [
			[ 'relation', null ],
			[ 'relationship', 'rea'.self::DELIM.'association' ],
			[ 'relationship', 'rea'.self::DELIM.'association'.self::DELIM.'child' ],
			[ 'relationship', 'rea'.self::DELIM.'association'.self::DELIM.'member' ],
			[ 'relationship', 'rea'.self::DELIM.'participation' ],
			[ 'relationship', 'rea'.self::DELIM.'custody' ],
			[ 'relationship', 'rea'.self::DELIM.'duality' ],
			[ 'relationship', 'rea'.self::DELIM.'stockflow' ],
			[ 'relationship', 'rea'.self::DELIM.'stockflow'.self::DELIM.'in' ],
			[ 'relationship', 'rea'.self::DELIM.'stockflow'.self::DELIM.'out' ],
			[ 'relationship', 'rea'.self::DELIM.'linkage' ],
		];
	}

	public function getFactoryTypeWithTypeIsArray()
	{
		return [
			[ 
				'relationship', 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'association' 
				]
			],
			[ 
				'relationship', 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'association',
					self::MAIN => "child"
				]
			],
			[ 
				'relationship', 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'association',
					self::MAIN => "member"
				]
			],
			[ 
				'relationship', 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'participation' 
				]
			],
			[ 
				'relationship', 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'custody' 
				]
			],
			[ 
				'relationship', 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'duality' 
				]
			],
			[ 
				'relationship', 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'stockflow' 
				]
			],
			[ 
				'relationship', 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'stockflow',
					self::MAIN => 'in'
				]
			],
			[ 
				'relationship', 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'stockflow',
					self::MAIN => 'out'
				]
			],
			[ 
				'relationship', 
				[
					self::DOMAIN => 'rea',
					self::CAT => 'linkage' 
				]
			],
		];
	}
}
