<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Contexture\ContextEntities\Flow;

use Eki\NRW\Mdl\Contexture\ContextEntities\Flow\DataFlow as BaseDataFlow;
use Eki\NRW\Component\Contexture\ContextEntities\Flow\Event\Event;
use Eki\NRW\Component\Contexture\ContextEntities\Flow\Event\GuardEvent;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
* Data flow 
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
class DataFlow extends BaseDataFlow
{
	/**
	* @var EventDispatcherInterface
	*/
	private $dispatcher;
	
	/**
	* Constructor
	* 
	* @param EventDispatcher $dispatcher
	* 
	*/
	public function __construct($name, EventDispatcher $dispatcher, \Closure $validator = null, \Closure $checker = null)
	{
		$this->dispatcher = $dispatcher;
		
		parent::__construct($name, $validator, $checker);		
	}
	
	/**
	* @inheritdoc
	* 
	*/
	protected function guard($data, $fromEntity, $toEntity, array $options)
	{
		$guardEvent = new GuardEvent(null, null, $fromEntity, $toEntity, $data, $option);
		$guardEvent = $this->dispatcher->dispatch(
			sprintf("dataflow.%s.guard", $this->getName()), 
			$guardEvent
		);
		
		return $guardEvent->isBlocked();
	}
	
	/**
	* @inheritdoc
	* 
	*/
	protected function before($data, $fromEntity, $toEntity, array $options)
	{
		$event = new Event(null, null, $fromEntity, $toEntity, $data, $option);
		$this->dispatcher->dispatch(
			sprintf("dataflow.%s.before", $this->getName()), 
			$event
		);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	protected function in($data, $fromEntity, $toEntity, array $options)
	{
		//...
	}
	
	/**
	* @inheritdoc
	* 
	*/
	protected function after($data, $fromEntity, $toEntity, array $options)
	{
		$event = new Event(null, null, $fromEntity, $toEntity, $data, $option);
		$this->dispatcher->dispatch(
			sprintf("dataflow.%s.after", $this->getName()), 
			$event
		);
	}
}
