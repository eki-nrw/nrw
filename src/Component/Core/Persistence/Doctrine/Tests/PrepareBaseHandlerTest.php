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

use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Common\Res\Metadata\Metadata;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\HandlerHelper;

use ReflectionClass;

class PrepareBaseHandlerTest extends PrepareAbstractHandlerTest
{
	/**
	* @var Metadata
	*/
	protected $metadata;
	
	/**
	* @var array
	*/
	protected $extraArgs = [];
	
	public function setUp()
	{
		parent::setUp();
		
		$this->handler = HandlerHelper::createBasePersistenceHandler(
			$this,
			$this->objectManager,
			$this->cache,
			$this->metadata,
			$this->handlerClass,
			[],
			$this->extraArgs
		);
	}
	
	public function tearDown()
	{
		parent::tearDown();
		
		$this->metadata = null;
	}
}

