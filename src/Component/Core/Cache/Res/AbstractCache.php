<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Cache\Res;

use Eki\NRW\Component\Core\Cache\AbstractCache as BaseAbstractCache;
use Symfony\Component\Cache\Adapter\AdapterInterface as SymfonyCache;

/**
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
abstract class AbstractCache extends BaseAbstractCache
{
	/**
	* @var array
	*/
	private $fixes;
	
	/**
	* @var string[]
	*/
	private $names = [];
	
	public function __construct(SymfonyCache $cache, array $fixes = [])
	{
		if (!isset($fixes['prefix']))
			$fixes['prefix'] = "";
		if (!isset($fixes['cache']))	
			$fixes['cache'] = "cache";
		if (!isset($fixes['tag']))	
			$fixes['tag'] = "tag";
			
		$this->fixes = $fixes;
				
		parent::__construct($cache);
	}

	/**
	* @inheritdoc
	* 
	*/
	protected function getCachePrefixFromObjectRef($ref)
	{
		return $this->fixes['prefix'] . "." . $ref . "-" . $this->fixes['cache'];
	}

	/**
	* @inheritdoc
	* 
	*/
	protected function getTagPrefixFromObjectRef($ref)
	{
		return $this->fixes['prefix'] . "." . $ref . "-" . $this->fixes['tag'];
	}
	
	/**
	* @inheritdoc
	* 
	*/	
	protected function getDefaultInfo()
	{
		return "id";
	}	
	
	/**
	* @inheritdoc
	* 
	*/
	protected function getClassRef($class)
	{
		if (!isset($this->names[$class]))
		{
			$this->names[$class] = $class;
		}
		
		return $this->names[$class];
	}
}
