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

use Eki\NRW\Component\Core\Persistence\Doctrine\AbstractHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\SimpleHelper;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\ArrayObjectHelper;

use PHPUnit\Framework\TestCase;

use stdClass;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class AbstractHandlerTest extends TestCase
{
    public function testSimpleConstructGoodForAbstractHandler()
    {
    	$handler = $this
    		->getMockBuilder(AbstractHandler::class)
    		->setConstructorArgs(array(
    			SimpleHelper::createObjectManager($this),
    			SimpleHelper::createCache($this)
    		))
    		->getMockForAbstractClass()
    	;
    }

	public function testObjectManagerBasedOnArrayObjects()
	{
		$arrayObjects = [];
		$repositories = [];
		$objectManager = ArrayObjectHelper::createObjectManager($this, $arrayObjects, $repositories);
		
		$obj = $objectManager->find(stdClass::class, 100);
		$this->assertNull($obj);
		
		$obj = new stdClass();
		$obj->id = null;
		$objectManager->persist($obj);

		$id = $obj->id;

		$findObj = $objectManager->find(stdClass::class, $id);
		$this->assertNotNull($findObj);
		
		$repository = $objectManager->getRepository("stdClass");
		$results = $repository->findBy(array('id' => $id));
		$this->assertNotEmpty($results);
		$result = $repository->findOneBy(array('id' => $id));
		$this->assertNotNull($result);
		$this->assertSame($result->id, $id);
		
		$findObj = $repository->find($id);
		$this->assertNotNull($findObj);
		$this->assertSame($findObj->id, $id);
	}

	public function testObjectManagerBasedOnArrayObjects_More()
	{
		$arrayObjects = [];
		$repositories = [];
		$objectManager = ArrayObjectHelper::createObjectManager($this, $arrayObjects, $repositories);
		
		$count = rand(10, 30);
		for($i=0;$i<$count;$i++)
		{
			$obj = new stdClass();
			$obj->id = null;
			$objectManager->persist($obj);

			$id = $obj->id;

			$findObj = $objectManager->find(stdClass::class, $id);
			$this->assertNotNull($findObj);
			
			$repository = $objectManager->getRepository(stdClass::class);
			$results = $repository->findBy(array('id' => $id));
			$this->assertNotEmpty($results);
			$result = $repository->findOneBy(array('id' => $id));
			$this->assertNotNull($result);
			$this->assertSame($result->id, $id);
			
			$findObj = $repository->find($id);
			$this->assertNotNull($findObj);
			$this->assertSame($findObj->id, $id);
		}		
	}
}

