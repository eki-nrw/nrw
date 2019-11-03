<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource\Creator;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Registry implements RegistryInterface
{
	/**
	* @var CreatorInterface[]
	*/
	private $creators = []; 
	
	public function __construct(array $creators = [])
	{
		foreach($creators as $creator)
		{
			$this->addCreator($creator);
		}	
	}
	
	public function addCreator(CreatorInterface $creator)
	{
		if (in_array($creator, $this->creators))
			throw new \InvalidArgumentException("Resource creator already added.");
		
		$this->creator[] = $creator;		
	}
	
	/**
	* Get appropriate creator
	* 
	* @param mixed $arguments
	* 
	* @return CreatorInterface
	*/
	public function getCreator($argument)
	{
		foreach($this->creators as $creator)
		{
			if ($creator->support($argument))
				return $creator;
		}
	}
}
