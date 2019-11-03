<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject;

use Eki\NRW\Mdl\Working\WorkingSubject\AbstractActionHandler as BaseAbstractActionHandler;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class AbstractActionHandler extends BaseAbstractActionHandler
{
	/**
	* @var string[]
	*/
	protected $classnames = [];
	
	public function __construct(array $classnames = [])
	{
		foreach($classnames as $classname)
		{
			if (!class_exists($classname))
				throw new \InvalidArgumentException("Class $classname don't exist.");
				
			$this->classnames[] = $classname;
		}
	}

	/**
	* @inheritdoc
	*/
	public function support($subject, $actionName)
	{
		return $this->supportSubject($subject) and $this->supportAction($actionName, $subject);
	}
	
	protected function supportSubject($subject)
	{
		foreach($this->classnames as $classname)
		{
			if ($subject instanceof $classname)
				return true;
		}
		
		return false;
	}

	/**
	* @internal
	* 
	* @param string $actionName
	* @param object $subject
	* 
	* @return bool
	*/
	abstract protected function supportAction($actionName, $subject);
}
