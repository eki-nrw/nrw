<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource\Type\Provider;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Registry implements RegistryInterface
{
	/**
	* @var ProviderInterface[]
	*/
	private $providers = []; 
	
	public function __construct(array $providers = [])
	{
		foreach($providers as $provider)
		{
			$this->addProvider($provider);
		}	
	}
	
	public function addProvider(ProviderInterface $provider)
	{
		if (in_array($provider, $this->providers))
			throw new \InvalidArgumentException("ResourceType provider already added.");
		
		$this->provider[] = $provider;		
	}
	
	/**
	* Get appropriate provider
	* 
	* @param mixed $arguments
	* 
	* @return ProviderInterface
	*/
	public function getProvider($argument)
	{
		foreach($this->providers as $provider)
		{
			if ($provider->support($argument))
				return $provider;
		}
	}
}
