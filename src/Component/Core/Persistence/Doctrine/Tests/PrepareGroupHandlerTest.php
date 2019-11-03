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

use Eki\NRW\Common\Res\Metadata\RegistryInterface;
use Eki\NRW\Common\Res\Metadata\Registry;

use Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper\HandlerHelper;

class PrepareGroupHandlerTest extends PrepareAbstractHandlerTest
{
	/**
	* @var \Eki\NRW\Common\Res\Metadata\RegistryInterface
	*/
	protected $registry;

	/**
	* @var array
	*/
	protected $extraArgs = [];
	
	public function setUp()
	{
		parent::setUp();
		
		$this->handler = HandlerHelper::createGroupPersistenceHandler(
			$this,
			$this->objectManager,
			$this->cache,
			$this->registry->getAll(),
			$this->handlerClass,
			[],
			$this->extraArgs
		);
	}
	
	public function tearDown()
	{
		parent::tearDown();

		$this->registry = null;		
	}
	
    protected function addToRegistry($alias, array $configuration)
    {
    	if ($this->registry === null)
			$this->registry = new Registry();
			
		$this->registry->addFrom($alias, $configuration);
		
		return $this->registry;
	}
}
