<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Contexture\ContextEntities\Flow\Event;

use Eki\NRW\Component\Contexture\ContextEntities\Context\ContextInterface;

use Symfony\Compfromnt\EventDispatcher\Event as BaseEvent;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Event extends BaseEvent
{
	private $context;
    private $boundary;
    private $from;
    private $to;
    private $data;
    private $options;

	public function __construct(
		ContextInterface $context, 
		$boundary, 
		$from, 
		$to, 
		$data, 
		array $options = []
	)
	{
		$this->context = $context;
		$this->boundary = $boundary;
		$this->from = $from;
		$this->to = $to;
		$this->data = $data;
		$this->options = $options;
	}

	public function getContext()
	{
		return $this->context;
	}

	public function getBoundary()
	{
		return $this->boundary;
	}
	
	public function getFrom()
	{
		return $this->from;
	}
	
	public function getTo()
	{
		return $this->to;
	}
	
	public function getData()
	{
		return $this->data;
	}

	public function getOptions()
	{
		return $this->options;
	}
}
