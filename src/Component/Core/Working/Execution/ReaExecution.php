<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Working\Execution;

use Eki\NRW\Common\Working\ExecutionTypeInterface;
use Eki\NRW\Component\REA\Event\EventInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ReaExecution extends Execution implements ReaExecutionInterface
{
	/**
	* @inheritdoc
	*/
	protected function matchExecutionType(ExecutionTypeInterface $executionType)
	{
		parent::matchExecutionType($executionType);

		if (!$executionType->is('rea'))
			throw new \InvalidArgumentException("Rea execution must be rea type.");
	}

	/**
	* @inheritdoc
	*/	
	public function getEvent()
	{
		return $this->getSubject();	
	}

	/**
	* @inheritdoc
	*/	
	public function setEvent(EventInterface $event = null)
	{
		$this->setSubject($event);
	}
	
	/**
	* @inheritdoc
	*/
	public function setSubject($subject)
	{
		if (!$subject instanceof EventInterface)
		{
			throw new \InvalidArgumentException(sprintf(
				"Execution subject must be instance of %s. Given %s.",
				EventInterface::class,
				get_class($subject)
			));
		}
		
		parent::setSubject($subject);
	}
}
