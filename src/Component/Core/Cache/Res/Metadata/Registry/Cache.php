<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Cache\Res\Metadata\Registry;

use Eki\NRW\Component\Core\Cache\Res\AbstractCache;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Res\Metadata\RegistryInterface;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;

use Symfony\Component\Cache\Adapter\AdapterInterface as SymfonyCache;

/**
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class Cache extends AbstractCache
{
	/**
	* @var RegistryInterface[];
	*/
	protected $registry = [];
	
	/**
	* @var string[]
	*/
	private $classLookup = [];
	
	/**
	* @var array[]
	*/
	private $prefixes = [];
	
	/**
	* @var string[]
	*/
	private $refs = [];

	public function __construct(SymfonyCache $cache, RegistryInterface $registry)
	{
		foreach($registry->getAll() as $alias => $metadata)
		{
			foreach($metadata->getClasses() as $name => $class)
			{
				$cacheKey = 
					$alias . "." . $name . "-" .
					$metadata->hasParameter("cache_key") ? $metadata->getParameter("cache_key") : "cache"
				;

				$cacheTag = 
					$alias . "." . $name . "-" .
					$metadata->getParameter("cache_tag") ? $metadata->getParameter("cache_tag") : "tag"
				;
				
				$this->prefixes[$name] = array(
					'key' => $cacheKey,
					'tag' => $cacheTag
				);
				
				$this->refs[$class] = $name;
			}
		}	
		
		$this->registry = $registry;
		
		parent::__construct($cache);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	protected function getCachePrefixFromObjectRef($ref)
	{
		return $this->prefixes[$ref]['key'];
	}

	/**
	* @inheritdoc
	* 
	*/
	protected function getTagPrefixFromObjectRef($ref)
	{
		return $this->prefixes[$ref]['tag'];
	}

	/**
	* @inheritdoc
	* 
	*/
	protected function getClassRef($class)
	{
		return $this->refs[$class];
	}
}
