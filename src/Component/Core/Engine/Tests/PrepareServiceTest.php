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

use Eki\NRW\Common\Res\Metadata\RegistryInterface;
use Eki\NRW\Common\Res\Metadata\Registry;

use Eki\NRW\Component\Core\Persistence\Tests\Helper\HandlerHelper;
use Eki\NRW\Component\Core\Engine\Tests\Helper\ServiceHelper;

use PHPUnit\Framework\TestCase;

use stdClass;

class PrepareServiceTest extends TestCase
{
	protected $handlerClass;
	protected $registry;
	
	protected $serviceSettings = [];
	protected $extraArgs = [];
	protected $serviceMethods = [];
	protected $serviceClass;
	protected $service;
	
	public function setUp()
	{
		// Need to prepare registry, serviceSettings, service class, persistence handler class, extra arguments, more service methods
		
		$this->service = ServiceHelper::createServiceSimple(
			$this,
			$this->serviceClass,
			$this->serviceSettings,
			$this->handlerClass,
			$this->registry,
			$this->extraArgs,
			$this->serviceMethods
		);
	}
	
	public function tearDown()
	{
		$this->handlerClass = null;
		$this->registry = null;
		
		$this->serviceSettings = null;
		$this->extraArgs = null;
		$this->serviceMethods = null;
		$this->service = null;
	}
	
    protected function addToRegistry($alias, array $configuration)
    {
    	if ($this->registry === null)
			$this->registry = new Registry();
			
		$this->registry->addFrom($alias, $configuration);
		
		return $this->registry;
	}
}
