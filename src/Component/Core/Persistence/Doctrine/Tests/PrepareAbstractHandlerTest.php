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

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\CommonHelper;
use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\ArrayObjectHelper;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Cache\ArrayCache;

use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;

use PHPUnit\Framework\TestCase;

class PrepareAbstractHandlerTest extends TestCase
{
	protected $handler;
	
	/**
	* @var string
	*/
	protected $handlerClass;
	
	/**
	* @var \Doctrine\Common\Persistence\ObjectManager
	*/
	protected $objectManager;
	
	/**
	* @var \Symfony\Component\Cache\Adapter\AdapterInterface
	*/
	protected $cache;
	
	protected $arrayObjects;
	protected $repositories;
	
	public function setUp()
	{
		$this->arrayObjects = [];
		$this->repositories = [];
		$this->objectManager = ArrayObjectHelper::createObjectManager($this, $this->arrayObjects, $this->repositories);
		$this->cache = CommonHelper::createArrayCache();
	}
	
	public function tearDown()
	{
		$this->objectManager = null;
		$this->cache = null;
		$this->arrayObjects = null;
		$this->repositories = null;
		$this->handlerClass = null;
		$this->handler = null;
	}
}
