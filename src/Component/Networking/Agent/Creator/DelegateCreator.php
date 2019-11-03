<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Agent\Creator;

use Eki\NRW\Component\Networking\Agent\AgentInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class DelegateCreator implements CreatorInterface
{
	private $creators = [];
	
	public function __construct(array $creators = [])
	{
		foreach($creators as $creator)
		{
			if (!$creator instanceof CreatorInterface)
				throw new \InvalidArgumentException(sprintf(
					"Creator must be instance of %s. Given %s.",
					CreatorInterface::class,
					get_class($creator)
				));
				
			$this->creator[] = $creator;
		}
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function support($argument)
	{
		foreach($this->creators as $creator)
		{
			if ($creator->support($argument) === true)
				return true;
		}
		
		return false;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function get($argument, array $contexts = [])
	{
		foreach($this->creators as $creator)
		{
			if ($creator->support($argument) === true)
			{
				return $creator->get($argument, $contexts);				
			}
		}
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getAll($argument, int $offset = 0, int $limit = 25, array $contexts = [])
	{
		foreach($this->creators as $creator)
		{
			if ($creator->support($argument) === true)
			{
				return $creator->getAll($argument, $offset, $limit, $contexts);				
			}
		}
		
		return array();
	}
}
