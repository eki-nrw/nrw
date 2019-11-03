<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Processing;

use Eki\NRW\Component\SPBase\Persistence\Processing\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\GroupHandler;

use Eki\NRW\Component\Core\Persistence\Doctrine\Processing\Event\Handler as EventHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Processing\Frame\Handler as FrameHandler;
//use Eki\NRW\Component\Core\Persistence\Doctrine\Processing\Pass\Handler as PassHandler;
//use Eki\NRW\Component\Core\Persistence\Doctrine\Processing\Process\Handler as ProcessHandler;
//use Eki\NRW\Component\Core\Persistence\Doctrine\Processing\Exchange\Handler as ExchangeHandler;

/**
 */
class Handler extends GroupHandler implements HandlerInterface
{
	/**
	* @var EventHandler
	*/
	protected $eventHandler;

	/**
	* @var FrameHandler
	*/
	protected $frameHandler;

	/**
	* @var PassHandler
	*/
	//protected $passHandler;

	/**
	* @var ProcessHandler
	*/
	//protected $processHandler;

	/**
	* @var ExchangeHandler
	*/
	//protected $exchangeHandler;

	/**
	* @inheritdoc
	* 
	*/
	public function eventHandler()
	{
		if ($this->eventHandler === null)
		{
			$this->eventHandler = new EventHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('event')
			);
		}
		
		return $this->eventHandler;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function frameHandler()
	{
		if ($this->frameHandler === null)
		{
			$this->eventHandler = new FrameHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('frame')
			);
		}
		
		return $this->frameHandler;
	}

	/**
	* @inheritdoc
	* 
	*/
/*	
	public function passHandler()
	{
		if ($this->passHandler === null)
		{
			$this->passHandler = new PassHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('pass')
			);
		}
		
		return $this->passHandler;
	}
*/

	/**
	* @inheritdoc
	* 
	*/
/*
	public function processHandler()
	{
		if ($this->processHandler === null)
		{
			$this->processHandler = new ProcessHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('process')
			);
		}
		
		return $this->processHandler;
	}
*/

	/**
	* @inheritdoc
	* 
	*/
/*
	public function exchangeHandler()
	{
		if ($this->exchangeHandler === null)
		{
			$this->exchangeHandler = new ExchangeHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('exchange')
			);
		}
		
		return $this->exchangeHandler;
	}
*/	
}
