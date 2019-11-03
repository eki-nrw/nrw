<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine;

use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\Base\Engine\PermissionResolver;
use Eki\NRW\Component\Base\Persistence\Processing\Handler;
use Eki\NRW\Component\Notification\NotificatorInterface;

use Eki\NRW\Component\Base\Engine\ProcessingService as ProcessingServiceInterface;

use Eki\NRW\Component\Core\Engine\Processing\EventService;
use Eki\NRW\Component\Core\Engine\Processing\FramingService;
//use Eki\NRW\Component\Core\Engine\Processing\PassService;
//use Eki\NRW\Component\Core\Engine\Processing\ProcessService;
//use Eki\NRW\Component\Core\Engine\Processing\ExchangeService;

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;
use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException as BaseInvalidArgumentException;
use Eki\NRW\Component\Base\Exceptions\UnauthorizedException as BaseUnauthorizedException;
use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

/**
 * Processing Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class ProcessingService extends BaseService implements ProcessingServiceInterface
{
	/**
	* @var Handler
	*/
	protected $processingHandler;
	
	/**
	* @var \Eki\NRW\Component\Base\Engine\Processing\EventService
	*/
	protected $eventService;

	/**
	* @var \Eki\NRW\Component\Base\Engine\Processing\FramingService
	*/
	protected $framingService;

	/**
	* @var \Eki\NRW\Component\Base\Engine\Processing\PassService
	*/
	//protected $passService;
	
	/**
	* @var \Eki\NRW\Component\Base\Engine\Processing\ProcessService
	*/
	//protected $processService;
	
	/**
	* @var \Eki\NRW\Component\Base\Engine\Processing\ExchangeService
	*/
	//protected $exchangeService;
	
	public function __construct(
		Engine $engine,
		array $settings,
		PermissionResolver $permissionResolver,
		NotificatorInterface $notificator,
		Handler $handler,
	)
	{
		$this->processingHandler = $handler;
		
		parent::__construct($engine, $settings, $permissionResolver, $notificator);
	}

	/**
	* Returns Event Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\Processing\EventService
	*/
	public function eventService()
	{
		if ($this->eventService === null)
		{
			$this->eventService = new EventService(
				$this->engine,
				$this->getSettings("event"),
				$this->permissionResolver,
				$this->notificator
				$this->processingHandler->eventHandler(),
			);
		}
		
		return $this->eventService;		
	}

	/**
	* Returns Framing Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\Processing\FramingService
	*/
	public function framingService()
	{
		if ($this->frameService === null)
		{
			$this->framingService = new FramingService(
				$this->engine,
				$this->getSettings("framing"),
				$this->permissionResolver,
				$this->notificator,
				$this->processingHandler->frameHandler()
			);
		}
		
		return $this->framingService;		
	}

	/**
	* Returns Pass Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\Processing\ProcessService
	*/
/*	
	public function passService()
	{
		if ($this->passService === null)
		{
			$this->passService = new PassService(
				$this->engine,
				$this->getSettings('pass'),
				$this->permissionResolver,
				$this->notificator,
				$this->processingHandler->passHandler(),
			);
		}
		
		return $this->passService;		
	}
*/
	
	/**
	* Returns Process Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\Processing\ProcessService
	*/
/*
	public function processService()
	{
		if ($this->processService === null)
		{
			$this->processService = new ProcessService(
				$this->engine,
				$this->getSettings('process'),
				$this->permissionResolver,
				$this->notificator,
				$this->processingHandler->processHandler(),
			);
		}
		
		return $this->processService;		
	}
*/
	
	/**
	* Returns Exchange Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\Processing\ExchangeService
	*/
/*
	public function exchangeService()
	{
		if ($this->exchangeService === null)
		{
			$this->exchangeService = new ExchangeService(
				$this->engine,
				$this->getSettings('exchange'),
				$this->permissionResolver,
				$this->notificator,
				$this->processingHandler->exchangeHandler(),
			);
		}
		
		return $this->exchangeService;		
	}
*/
}
