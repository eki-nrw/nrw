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

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\BaseBaseHandlerTest;
use Eki\NRW\Component\Core\Persistence\Doctrine\Relating\Handler;
use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;

use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Relating\Fixtures\Relation;
use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Relating\Fixtures\AObject;
use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Relating\Fixtures\BObject;
use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Relating\Fixtures\CObject;
use Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Relating\Fixtures\Res;

use ReflectionClass;

class HandlerTest extends BaseBaseHandlerTest
{
	private $handler;

	public function setUp()
	{
		$this->handler = $this->createBasePersistenceHandler(
			$this->createObjectManager(),
			$this->createCache(),
			$this->configMetadata(
				"relating", 
				array(
					'default' => Relation::class,
					'relation' => Relation::class,
					'relationship' => Relation::class,
					'group' => Relation::class,
				),
				array(
					'cache_prefix' => 'relating',
					'cache_tag' => 'relating'
				)
			),
			Handler::class
		);
	}
	
	public function tearDown()
	{
		$this->handler = null;
	}

	public function testSimpleCreateRelation()
	{
		$handler = $this->handler;
		
		$relation = $handler->createRelation("relation", null);
		
		$this->assertNotNull($relation);
		$this->assertInstanceOf(Relation::class, $relation);
	}
		
	public function testCreateRelationNoType()
	{
		$handler = $this->handler;

		$relation = $handler->createRelation("relation", null);
		
		$this->assertNotNull($relation);
		$this->assertInstanceOf(Relation::class, $relation);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	* 
	*/
	public function testCreateRelationWrongIdentifier()
	{
		$handler = $this->handler;
		
		$relation = $handler->createRelation("relation_x", null);
	}

	public function testCreateRelationWithType()
	{
		$handler = $this->handler;
		
		$relation = $handler->createRelation("relation", "type_of_relation");
		
		$this->assertNotNull($relation);
	}

	public function testCreateRelationship()
	{
		$handler = $this->handler;
		
		$relationship = $handler->createRelation("relationship", null);
		
		$this->assertNotNull($relationship);
	}

	public function testCreateGroup()
	{
		$handler = $this->handler;
		
		$group = $handler->createRelation("group", null);
		
		$this->assertNotNull($group);
	}

	public function testLoadRelation()
	{
		$handler = $this->handler;		
		
		$relation = $handler->createRelation("relation", null);

		$id = $relation->getId();
		
		$loadedRelation = $handler->loadRelation($id);
		
		$this->assertNotNull($loadedRelation);
		$this->assertSame($id, $loadedRelation->getId());
	}

	public function testDeleteRelation()
	{
		$handler = $this->handler;
		
		$relation = $handler->createRelation("relation", null);

		$this->handler->deleteRelation($relation);
	}

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\NotFoundException
	* 
	*/
	public function testDeleteRelationThenLoad()
	{
		$handler = $this->handler;
		
		$relation = $handler->createRelation("relation", null);

		$id = $relation->id;
		$this->handler->deleteRelation($relation);
		$this->handler->loadRelation($id);
	}

	public function testUpdateRelation()
	{
		$handler = $this->handler;
		
		$relation = $handler->createRelation("relation", null);
		$relation->setName("name initialized");
		
		$id = $relation->getId();

		$loadedRelation = $handler->loadRelation($id);
		$this->assertSame("name initialized", $loadedRelation->getName());
		$loadedRelation->setName("name modified");
		
		$handler->updateRelation($loadedRelation);
		$loadedRelation = $handler->loadRelation($id);
		$this->assertSame("name modified", $loadedRelation->getName());
	}
	
	public function testGetRelations()
	{
		$handler = $this->handler;
		$objectHandler = $this->createNullPersistenceHandler();
		
		$obj = $objectHandler->create('std');
		$this->assertNotNull($obj);
		
		$relation_1 = $handler->createRelation("relation", null);
		$this->assertSame("relation", $relation_1->identifier);
		$relation_1->setBase($obj);
		
		$relation_2 = $handler->createRelation("relation", null);
		$relation_2->setBase($obj);

		$relation_3 = $handler->createRelation("relation", null);
		$relation_3->setBase($obj);

		$relations = $handler->getRelations(
			array(
				'id' => $obj->getId(),
				'class' => get_class($obj),
				'base' => true
			),
			'relation', null
		);
				
		$this->assertSame(3, count($relations));
	}

	public function testGetRelationsBetween()
	{
		$handler = $this->handler;
		$objectHandler = $this->createNullPersistenceHandler();
		
		$obj_1 = $objectHandler->create('default');
		$obj_2 = $objectHandler->create('std');
		$obj_3 = $objectHandler->create('std');
		
		// Between object 1 and object 2
		$relationship_1_2_A = $handler->createRelation("relationship", null);
		$relationship_1_2_A->setBase($obj_1);
		$relationship_1_2_A->addOther($obj_2, "object_2");
		
		$relationships = $handler->getRelationsBetween(
			array(
				'id' => $obj_1->getId(),
				'class' => get_class($obj_1)
			),
			array(
				'id' => $obj_2->getId(),
				'class' => get_class($obj_2)
			),
			'relationship', null
		);
				
		$this->assertSame(1, count($relationships));
		
		// Betwwen object 2 and object 3: 1 relation and 1 relationship
		$relation_base_2_others_3_1 = $handler->createRelation("group", null);
		$relation_base_2_others_3_1->setBase($obj_2);
		$relation_base_2_others_3_1->addOther($obj_3, "object_3");
		$relation_base_2_others_3_1->addOther($obj_1, "object_1");

		$relationship_2_3 = $handler->createRelation("relationship", null);
		$relationship_2_3->setBase($obj_2);
		$relationship_2_3->addOther($obj_3, "object 3");

		$relationships_2_3 = $handler->getRelationsBetween(
			array(
				'id' => $obj_2->getId(),
				'class' => get_class($obj_2)
			),
			array(
				'id' => $obj_3->getId(),
				'class' => get_class($obj_3)
			),
			'relationship', null
		);

		$relations_2_3 = $handler->getRelationsBetween(
			array(
				'id' => $obj_2->getId(),
				'class' => get_class($obj_2)
			),
			array(
				'id' => $obj_3->getId(),
				'class' => get_class($obj_3)
			),
			'group', null
		);

		$all_relations_2_3 = $handler->getRelationsBetween(
			array(
				'id' => $obj_2->getId(),
				'class' => get_class($obj_2)
			),
			array(
				'id' => $obj_3->getId(),
				'class' => get_class($obj_3)
			)
		);
		
		$this->assertSame(1, count($relationships_2_3));
		$this->assertSame(1, count($relations_2_3));
		$this->assertSame(2, count($all_relations_2_3));
	}

	private function createNullPersistenceHandler()
	{
		$handler = $this->createBasePersistenceHandler(
			$this->createObjectManager(),
			$this->createCache(),
			$this->configMetadata(
				"testing", 
				array(
					'default' => Res::class,
					'std' => Res::class,
					'def' => Res::class
				),
				array(
					'cache_prefix' => 'testing',
					'cache_tag' => 'testing'
				)
			),
			BaseHandler::class,
			['create', 'load', 'delete', 'update']
		);

		$handler
			->expects($this->any())
			->method('create')
			->will($this->returnCallback(function ($identifier) use ($handler) {
				$meta = $handler->getMetadata();
				$cl = $meta->getClass($identifier);
				$obj = new $cl();
				$handler->update($obj);
				return $obj;
			}))
		;

		$handler
			->expects($this->any())
			->method('load')
			->will($this->returnCallback(function ($id) use ($handler) {
				$r = new ReflectionClass($handler);
				
				$m = $r->getMethod('getObjectFromCache');
				$m->setAccessible(true);
				if(null !== ($obj = $m->invokeArgs($handler, [$id])))
					return $obj;

				$m = $r->getMethod('findRes');
				$m->setAccessible(true);
				$loadedObj = $m->invokeArgs($handler, [$id]);
				if (null === $loadedObj)
		            throw new \RuntimeException("Object with id $id not found");

				$m = $r->getMethod('setObjectToCache');
				$m->setAccessible(true);
				$m->invokeArgs($handler, [$loadedObj]);
				
				return $loadedObj;
			}))
		;

		$handler
			->expects($this->any())
			->method('delete')
			->will($this->returnCallback(function ($obj) use ($handler) {
				$r = new ReflectionClass($handler);
				$m = $r->getMethod('clearObjectFromCache');
				$m->setAccessible(true);
				$m->invokeArgs($handler, [$obj]);
				$handler->getObjectManager()->remove($obj);
			}))
		;

		$handler
			->expects($this->any())
			->method('update')
			->will($this->returnCallback(function ($obj) use ($handler) {
				$r = new ReflectionClass($handler);
				$m = $r->getMethod('setObjectToCache');
				$m->setAccessible(true);
				$m->invokeArgs($handler, [$obj]);
				$handler->getObjectManager()->persist($obj);
			}))
		;

		return $handler;
	}
}

