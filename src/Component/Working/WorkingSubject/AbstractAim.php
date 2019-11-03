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

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractAim implements AimInterface
{
	protected $name;
	protected $types = [];
	protected $options = [];
	
	public function __construct($name, $types, array $options = [])
	{
		if (is_string($types))
			$types = array($types);
		if (!is_array($types))
			throw new \InvalidArgumentException("Parameter 'types' must be array or string.");
		
		$this->name = $name;
		$this->types = $types;
		$this->options = $options;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function support($name, $subject)
	{
		return 
			$this->_supportName($name)
			and 
			$this->_supportType($subject)
			and 
			$this->_supportMore($name, $subject)
		;
	}
	
	protected function _supportName($name)
	{
		return $name === $this->name;
	}
	
	protected function _supportType($subject)
	{
		if (null === ($tool = $this->getTool()))
			return false;
			
		return in_array($tool->getSubjectType($subject), array_values($this->types), true);
	}
	
	protected function _supportMore($name, $subject)
	{
		return true;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function aim($name, $subject, array $contexts = [])
	{
		if ($this->support($name, $subject) !== true)
		{
			throw new \InvalidArgumentException(sprintf(
				"This aim don't support name $name and/or subject"
			));			
		}
		
		return $this->onAim($name, $subject, $contexts);
	}
	
	protected function getOption($key)
	{
		if (isset($this->options[$key]))
			return $this->options[$key];
	}
	
	public function setTool(ToolInterface $tool)
	{
		$this->options['workingTool'] = $tool;		
	}

	protected function getTool()
	{
		return $this->getOption('workingTool');		
	}

	public function setLogger(LoggerInterface $logger = null)
	{
		if ($logger === null)
		{
			if (isset($this->options['workingLogger']))
				unset($this->options['workingLogger']);
		}
		else
		{
			$this->options['workingLogger'] = $logger;		
		}
	}

	protected function Logger()
	{
		$logger = $this->getOption('workingLogger');
		if ($logger)
		{
			return $logger;
		}
		else 
		{
			return new NullLogger;
		}
	}
	
	/**
	* Core function 
	* 
	* @param string $name
	* @param mixed $subject
	* @param array $contexts
	* 
	* @return array|null
	*/	
	abstract protected function onAim($name, $subject, array $contexts);
}
