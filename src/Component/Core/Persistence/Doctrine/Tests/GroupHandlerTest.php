<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests;

use Eki\NRW\Component\Core\Persistence\Doctrine\GroupHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\SimpleHelper;
use Eki\NRW\Common\Res\Metadata\RegistryInterface;
use Eki\NRW\Common\Res\Metadata\Metadata;

use PHPUnit\Framework\TestCase;

use stdClass;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class GroupHandlerTest extends TestCase
{
    public function testSimpleConstructGood()
    {
    	$handler = $this
    		->getMockBuilder(GroupHandler::class)
    		->setConstructorArgs(array(
    			SimpleHelper::createObjectManager($this),
    			SimpleHelper::createCache($this),
    			array()
    		))
    		->getMockForAbstractClass()
    	;
    }

	/**
	* @dataProvider getArrayMetadataGood
	* 
	*/
    public function testConstructGood(array $metadatas)
    {
    	$handler = $this
    		->getMockBuilder(GroupHandler::class)
    		->setConstructorArgs(array(
    			SimpleHelper::createObjectManager($this),
    			SimpleHelper::createCache($this),
    			$metadatas
    		))
    		->getMockForAbstractClass()
    	;
    }
    
    public function getArrayMetadataGood()
    {
		return [
			[ 
				array(
					new Metadata("alias_1", array()),
					new Metadata("alias_2", array()),
			    ) 
			],
			[ 
				array(
					new Metadata("alias_x", array()),
					new Metadata("alias_y", array()),
					new Metadata("alias_z", array()),
			    ) 
			],
			[ 
				array(
					new Metadata("alias_A", 
						array(
							'ref_1' => stdClass::class,
							'ref_2' => stdClass::class,
						)
					),
					new Metadata("alias_B", array()),
			    ) 
			],
			[
			    array(
			    	'alias_1' => array(
			    		'classes' => array(),
			    	),
			    	'alias_2' => array(
			    		'classes' => array(),
			    	)
			    )
			],
			[
			    array(
			    	'alias_1' => array(
			    		'classes' => array(
			    			'ref_1' => stdClass::class,
			    			'ref_2' => stdClass::class,
			    		),
			    	),
			    	'alias_2' => array(
			    		'classes' => array(
			    			'ref_1' => stdClass::class,
			    			'ref_2' => stdClass::class,
			    		),
			    		'parameters' => array(
			    			'param1' => 100,
			    			'param2' => 'KJKJKJKJ'
			    		)
			    	),
			    )
			],
		];
	}

	/**
	* @dataProvider getArrayMetadataWrong
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
    public function testConstructWrong(array $metadatas)
    {
    	$handler = $this
    		->getMockBuilder(GroupHandler::class)
    		->setConstructorArgs(array(
    			SimpleHelper::createObjectManager($this),
    			SimpleHelper::createCache($this),
    			$metadatas
    		))
    		->getMockForAbstractClass()
    	;
    }

    public function getArrayMetadataWrong()
    {
		return [
			[ 
				array(
					"kjskfjdskkdaskd",
					array(),
			    ) 
			],
			[ 
				array(
					new stdClass,
			    ) 
			],
			[
				array(
					array(),
					100
				)
			],
		];
	}
}
