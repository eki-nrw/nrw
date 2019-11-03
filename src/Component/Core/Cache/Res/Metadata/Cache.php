<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Cache\Res\Metadata;

use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Component\Core\Cache\Res\AbstractCache;

use Symfony\Component\Cache\Adapter\AdapterInterface as SymfonyCache;

/**
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class Cache extends AbstractCache
{
	/**
	* @var MetadataInterface[];
	*/
	protected $metadata;
	
	/**
	* @var string[]
	*/
	private $names = [];

	/**
	* @var string
	*/	
	protected $defaultRef;
	
	public function __construct(SymfonyCache $cache, MetadataInterface $metadata)
	{
		$classes = $metadata->getClasses();
		foreach($classes as $name => $class)
		{
			if (isset($this->names[$class]))
				throw new \InvalidArgumentException("Parameter 'metadata' has more than one class $class.");
			
			$this->names[$class] = $name;
		}

		$defaultRef = 'def';
		if ($metadata->hasParameter("default_ref"))
			$defaultRef = $metadata->getParameter("default_ref");
		$keys = array_keys($classes);
		if (!empty($classes) and !in_array($defaultRef, $keys))
			list($defaultRef) = $keys;
		$this->defaultRef = $defaultRef;
			
echo "defaultRef=".$defaultRef."\n";	
		
		$fixes = array();
		$fixes['prefix'] = $metadata->getAlias();
		if ($metadata->hasParameter("cache_key"))
			$fixes['cache'] = $metadata->getParameter("cache_key");
		if ($metadata->hasParameter("cache_tag"))
			$fixes['tag'] = $metadata->getParameter("cache_tag");

		$this->metadata = $metadata;
		
		parent::__construct($cache, $fixes);
	}

	/**
	* @inheritdoc
	* 
	*/
/*	
	protected function getCachePrefixFromObjectRef($ref)
	{
		return 
			$this->metadata->getAlias(). "." . $ref . "-" .
			$this->metadata->hasParameter("cache_key") ? $this->metadata->getParameter("cache_key") : "cache"
		;
	}
*/

	/**
	* @inheritdoc
	* 
	*/
/*	
	protected function getTagPrefixFromObjectRef($ref)
	{
		return 
			$this->metadata->getAlias(). "." . $ref . "-" .
			$this->metadata->hasParameter("cache_tag") ? $this->metadata->getParameter("cache_tag") : "tag"
		;
	}
*/

	/**
	* @inheritdoc
	* 
	*/
	protected function getClassRef($class)
	{
echo "clasRef=".$this->names[$class]."\n";
		return $this->names[$class];
	}

	/**
	* @inheritdoc
	* 
	*/
	protected function getDefaultRef()
	{
		return $this->defaultRef;
	}
}
