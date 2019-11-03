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

use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Common\Res\Model\ResInterface;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Fixtures\InternalRes;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Fixtures\WrapHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\SimpleHelper;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\CommonHelper;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\ArrayObjectHelper;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

use PHPUnit\Framework\TestCase;
use \stdClass;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class BaseHandlerTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}
	
    public function testSimpleConstructGood()
    {
    	$handler = $this
    		->getMockBuilder(BaseHandler::class)
    		->setConstructorArgs(array(
    			SimpleHelper::createObjectManager($this),
    			SimpleHelper::createCache($this),
				new Metadata(
    				"an_alias",
    				array('default' => stdClass::class),
    				array(
    					'cache_prefix' => 'stdClass',
    					'cache_tag' => 'stdClass'
    				)
    			)
    		))
    		->getMockForAbstractClass()
    	;

    	$otherHandler = $this
    		->getMockBuilder(BaseHandler::class)
    		->setConstructorArgs(array(
    			SimpleHelper::createObjectManager($this),
    			SimpleHelper::createCache($this),
				new Metadata(
    				"other_alias",
    				stdClass::class,
    				array(
    					'cache_prefix' => 'stdClass',
    					'cache_tag' => 'stdClass'
    				)
    			)
    		))
    		->getMockForAbstractClass()
    	;
    }

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
    public function testConstructMetadaNoClasses()
    {
    	$handler = $this
    		->getMockBuilder(BaseHandler::class)
    		->setConstructorArgs(array(
    			SimpleHelper::createObjectManager($this),
    			SimpleHelper::createCache($this),
    			new Metadata("an_alias", array())
    		))
    		->getMockForAbstractClass()
    	;
    }

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
    public function testConstructMetadaNoCachePrefix()
    {
    	$handler = $this
    		->getMockBuilder(BaseHandler::class)
    		->setConstructorArgs(array(
    			SimpleHelper::createObjectManager($this),
    			SimpleHelper::createCache($this),
    			new Metadata(
    				"an_alias", 
    				array(
    					'default'=> stdClass::class
    				),
    				array(
    					'cache_tag' => 'tag'
    				)
    			)
    		))
    		->getMockForAbstractClass()
    	;
    }

	/**
	* @expectedException \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException
	*/
    public function testConstructMetadaNoCacheTag()
    {
    	$handler = $this
    		->getMockBuilder(BaseHandler::class)
    		->setConstructorArgs(array(
    			SimpleHelper::createObjectManager($this),
    			SimpleHelper::createCache($this),
    			new Metadata(
    				"an_alias", 
    				array(
    					'default' => stdClass::class
    				),
    				array(
    					'cache_prefix' => 'prefix'
    				)
    			)
    		))
    		->getMockForAbstractClass()
    	;
    }
    
    public function testGetCacheItemNoRes()
    {
		$handler = $this->createWrapHandler(
			"a_res_type", 
			array('default' => stdClass::class),
			array(
				'cache_prefix' => "res",
				'cache_tag' => "restag"
			)
		);
		
		$this->assertNotNull($handler->___getCacheItem(100));
	}

    public function testGetCacheItemRes()
    {
		$handler = $this->createWrapHandler(
			"a_res_type", 
			array('default' => InternalRes::class),
			array(
				'cache_prefix' => "res",
				'cache_tag' => "restag"
			)
		);

		$id = 99;
		$res = new InternalRes($id);
		$handler->___setCacheItem($res);		
		$cacheItem = $handler->___getCacheItem($id);
		$this->assertNotNull($cacheItem);
		$this->assertTrue($cacheItem->isHit());
		$obj = $cacheItem->get();
		$this->assertNotNull($obj);
		$this->assertInstanceOf(ResInterface::class, $obj);
	}

    public function testDeleteCacheItemRes()
    {
		$handler = $this->createWrapHandler(
			"a_res_type", 
			array('default' => InternalRes::class),
			array(
				'cache_prefix' => "res",
				'cache_tag' => "restag"
			)
		);

		$id = 99;

		$cacheItem = $handler->___getCacheItem($id);
		$this->assertFalse($cacheItem->isHit());

		$res = new InternalRes($id);
		$handler->___setCacheItem($res);		
		$cacheItem = $handler->___getCacheItem($id);
		$this->assertNotNull($cacheItem);
		$this->assertTrue($cacheItem->isHit());

		$handler->___deleteCacheItem($res);
		$cacheItem = $handler->___getCacheItem($res);
		$this->assertFalse($cacheItem->isHit());
	}

    public function testFindRes()
    {
		$handler = $this->createWrapHandler(
			"a_res_type", 
			array('default' => InternalRes::class),
			array(
				'cache_prefix' => "res",
				'cache_tag' => "restag"
			)
		);

		$id = 101;
		$res = new InternalRes($id);
		$handler->addRes($res, 'default');
		$foundRes = $handler->___findRes($id, 'default');
		$this->assertNotNull($foundRes);
		$this->assertSame($res, $foundRes);
	}

    public function testFindResOneBy()
    {
		$handler = $this->createWrapHandler(
			"a_res_type", 
			array('default' => InternalRes::class),
			array(
				'cache_prefix' => "res",
				'cache_tag' => "restag"
			)
		);

		$id = 101;
		$res = new InternalRes($id);
		$res->prop_a = "a";
		$res->prop_b = "bb";
		$handler->addRes($res, 'default');
		$foundRes = $handler->___findResOneBy(array('prop_a' => 'a', 'prop_b' => 'bb'), 'default');
		$this->assertNotNull($foundRes);
		$this->assertSame($res, $foundRes);
	}

    private function createWrapHandler($resIdentifier, array $resClasses, array $resParameters)
    {
		$arrayObjects = [];
		$repositories = [];
		$objectManager = ArrayObjectHelper::createObjectManager($this, $arrayObjects, $repositories);
		$cache = CommonHelper::createArrayCache();

    	$handler = new WrapHandler(
			$objectManager,
			$cache,
    		new Metadata($resIdentifier, $resClasses, $resParameters)
    	);
    	
    	return $handler;
	}
}
