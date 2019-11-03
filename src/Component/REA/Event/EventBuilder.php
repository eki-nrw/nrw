<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Event;

use Eki\NRW\Common\Extension\AbstractBuilder;
use Eki\NRW\Common\Extension\CreateBuilderInterface;
use Eki\NRW\Common\Res\Factory\FactoryInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class EventBuilder extends AbstractBuilder implements CreateBuilderInterface
{
	/**
	* @var EventTypeInterface
	*/
	protected $eventType;
	
	/**
	* @inheritdoc
	*/
	public function __construct(EventTypeInterface $eventType, FactoryInterface $factory, array $extensions = [])
	{
		$this->eventType = $eventType;
		
		parent::__construct($eventType->getEventType(), $factory, $extensions);		
	}

	/**
	* @inheritdoc
	*/	
	protected function initialize($object)
	{
		parent::initialize($object);
		
		$object->setEventType($this->eventType);
	}

	/**
	* @inheritdoc
	*/	
	protected function buildFromDependencyInjection($object)
	{
		$this->eventType->buildEvent($this, array('event_object' => $object, 'position' => 'build'));
	}
	
	/**
	* @inheritdoc
	*/
	public function createBuilder($type)
	{
		if (!$type instanceof EventTypeInterface)
			throw new \InvalidArgumentException(sprintf(
				"Parameter 'type' must be instance of %s. Given %s.",
				EventTypeInterface::class,
				gettype($type)
			));
		
		$thisClass = get_class($this);
		
		return new $thisClass($type, $this->getFactory(), array_values($this->getExtensions()));
	}	
}
