<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Agent\Provider;

use Eki\NRW\Component\Networking\Agent\AgentInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class DelegateProvider implements ProviderInterface
{
	private $providers = [];
	
	public function __construct(array $providers = [])
	{
		foreach($providers as $provider)
		{
			if (!$provider instanceof ProviderInterface)
				throw new \InvalidArgumentException(sprintf(
					"Provider must be instance of %s. Given %s.",
					ProviderInterface::class,
					get_class($provider)
				));
				
			$this->provider[] = $provider;
		}
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function support($argument)
	{
		foreach($this->providers as $provider)
		{
			if ($provider->support($argument) === true)
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
		foreach($this->providers as $provider)
		{
			if ($provider->support($argument) === true)
			{
				return $provider->get($argument, $contexts);				
			}
		}
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getAll($argument, int $offset = 0, int $limit = 25, array $contexts = [])
	{
		foreach($this->providers as $provider)
		{
			if ($provider->support($argument) === true)
			{
				return $provider->getAll($argument, $offset, $limit, $contexts);				
			}
		}
		
		return array();
	}
}
