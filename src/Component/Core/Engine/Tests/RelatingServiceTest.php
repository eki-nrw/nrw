<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Tests;

use Eki\NRW\Component\Core\Engine\RelatingService;
use Eki\NRW\Component\Core\Persistence\Handler;

use Eki\NRW\Component\Relating\Relation\Relation;
use Eki\NRW\Component\Relating\Relation\Relationship;
use Eki\NRW\Component\Relating\Relation\Group;
use Eki\NRW\Component\Core\Persistence\Tests\Relating\Fixtures\Relation as PsRelation;

use Eki\NRW\Component\Core\Persistence\Tests\Helper\CommonHelper;
use Eki\NRW\Component\Core\Engine\Tests\Helper\ServiceHelper;

use PHPUnit\Framework\TestCase;

use stdClass;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class RelatingServiceTest extends PrepareServiceTest
{
	protected $nullService;
	
	public function setUp()
	{
		$this->addToRegistry("relating",
			array(
				'classes' => array(
					'default' => PsRelation::class,
					'relation' => PsRelation::class,
					'relationship' => PsRelation::class,
					'group' => PsRelation::class,
				),
				'cache_prefix' => 'relating',
				'cache_tag' => 'relating'
			)
		);
		
		$this->serviceSettings = array(
			'factory' => array(
				'relation' => Relation::class,
				'relationship' => Relationship::class,
				'group' => Group::class,
			),
		);
		
		$this->serviceClass = RelatingService::class;
		$this->handlerClass = Handler::class;

		$this->extraArgs = [
			CommonHelper::createArrayCache()
		];
		
		parent::setUp();
		
		$this->nullService = ServiceHelper::createNullServiceSimple(
			$this,
			$this->service->getHandler()->getObjectManager(),
			$this->service->getHandler()->getCache(),
			[]
		);
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}

	/**
	* @dataProvider getValidIdentifier
	*/
	public function testCreateRelation($identifier)
	{
		$service = $this->service;
		
		$relation = $service->createRelation($identifier, null);
		
		$this->assertNotNull($relation);
	}

	/**
	* @dataProvider getValidIdentifier
	*/
	public function testLoadRelation($identifier)
	{
		$service = $this->service;
		
		$relation = $service->createRelation($identifier, null);
		$loadedRelation = $service->loadRelation($relation->getId());

		$this->assertNotNull($relation);
		$this->assertNotNull($loadedRelation);
		$this->assertSame($loadedRelation->getId(), $relation->getId());
	}

	/**
	* @dataProvider getValidIdentifier
	*/
	public function testUpdateRelation($identifier)
	{
		$service = $this->service;
		
		$relation = $service->createRelation($identifier, null);
		$relation->setName("Name to update");
		$service->updateRelation($relation);

		$loadedRelation = $service->loadRelation($relation->getId());
		$this->assertSame("Name to update", $loadedRelation->getName());
		
		$relation->setName("Name to re-update");
		$service->updateRelation($relation);
		$loadedRelation = $service->loadRelation($relation->getId());
		$this->assertSame("Name to re-update", $loadedRelation->getName());
	}

	public function testUpdateRelationship()
	{
		$service = $this->service;

		$nullService = $this->nullService;
		
		$relationship = $service->createRelation("relationship", null);
		$objBase = $nullService->create("def");
		$objBase->name = "base";
		$objOther = $nullService->create("std");
		$objOther->name = "other";
		
		$rs = $service->linkRelationship($relationship, $objBase, $objOther);
		
		$loadedRelationship = $service->loadRelation($rs->getId());
		$oBase = $loadedRelationship->getObject();
		$oOther = $loadedRelationship->getOtherObject();
		$this->assertSame("base", $oBase->name);		
		$this->assertSame("other", $oOther->name);		
	}

	/**
	* @dataProvider getValidIdentifier
	*/
	public function testDeleteRelation($identifier)
	{
		$service = $this->service;
		
		$relation = $service->createRelation($identifier, null);
		$service->deleteRelation($relation);
	}

	/**
	* @dataProvider getValidIdentifier
	* $expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	*/
	public function testDeleteThenLoadRelation($identifier)
	{
		$service = $this->service;
		
		$relation = $service->createRelation($identifier, null);
		$relationId = $relation->getId();
		$service->deleteRelation($relation);
		$service->loadRelation($relationId);
	}
	
	public function getValidIdentifier()
	{
		return [
			[ 'relation' ],
			[ 'relationship' ],
			[ 'group' ],
		];
	}
	
	public function testLinkRelationship()
	{
		$service = $this->service;
		$nullService = $this->nullService;
		
		$relationship = $service->createRelation("relationship", null);
		$objBase = $nullService->create("def");
		$objOther = $nullService->create("std");
		
		$this->assertNotNull($objBase);
		$this->assertNotNull($objOther);
		
		$rs = $service->linkRelationship($relationship, $objBase, $objOther);
		$this->assertNotNull($rs);
		$this->assertSame($objBase->getId(), $rs->getNode()->getObject()->getId());
		$this->assertSame($objOther->getId(), $rs->getOtherNode()->getObject()->getId());
		$this->assertSame($objBase->getId(), $rs->getObject()->getId());
		$this->assertSame($objOther->getId(), $rs->getOtherObject()->getId());
	}
}

