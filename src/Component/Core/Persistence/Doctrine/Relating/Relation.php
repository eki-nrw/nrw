<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Relating;

use Eki\NRW\Component\SPBase\Persistence\Relating\Relation as RelationInterface;
use Eki\NRW\Common\Relations\HasParametersTrait;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Relation implements RelationInterface
{
	use
		HasParametersTrait
	;

	/**
	* @var mixed
	*/
	public $id;
	
	/**
	* @var string
	*/
	public $identifier;
	
	/**
	* @var string
	*/
	public $type;
	
	/**
	* @var string
	*/
	public $name;
	
	/**
	* @var string
	*/
	protected $baseString;

	/**
	* @var string
	*/	
	protected $othersString;
	
	/**
	* @inheritdoc
	* 
	*/
	public function getId()
	{
		return $this->id;
	}
	
	/**
	* @inheritdoc
	*/
	public function getBase()
	{
		if ($this->baseString === null)
			return null;
			
		return unserialize($this->baseString);
	}
	
	/**
	* @inheritdoc
	*/
	public function setBase($base = null)
	{
		if ($base === null)
			$this->baseString = null;
		else
		{
			if (!is_object($base) and !is_array($base))
				throw new InvalidArgumentException("base", "Parameter 'base' must be array or object.");
			
			if (is_object($base))
			{
				if (!method_exists($base, "getId"))
					throw new InvalidArgumentException("base", "Parameter 'base' is object that must have method 'getId'.");
				
				$b = [];
				$b['id'] = $base->getId();
				$b['class'] = get_class($base);

				$this->baseString = serialize($b);
			}
			else // array
			{
				if (!isset($base['id']) or !isset($base['class']))				
					throw new InvalidArgumentException("base", "Parameter 'base' must be array that has index 'id' and 'class'.");
				$this->baseString = serialize($base);
			}
		}	
	}
	
	/**
	* @inheritdoc
	*/
	public function getOthers()
	{
		if ($this->othersString === null)
			return array();
		else
		{
			$others = [];
			$storeOthers = unserialize($this->othersString);
			foreach($storeOthers as $key => $storeOther)
			{
				$others[$key] = unserialize($storeOther);
			}
			
			return $others;
		}
	}
	
	/**
	* @inheritdoc
	*/
	public function setOthers(array $others = [])
	{
		$storeOthers = [];
		foreach($others as $key => $other)
		{
			if (!is_object($other) and !is_array($other))
				throw new InvalidArgumentException("other", "Parameter 'other' of index '$key' must be array or object.");
			
			if (is_object($other))
			{
				if (!method_exists($other, "getId"))
					throw new InvalidArgumentException("others", "Parameter 'other' of key $key is object that must have method 'getId'.");

				$o = [];
				$o['id'] = $other->getId();
				$o['class'] = get_class($other);

				$storeOthers[$key] = serialize($o);
			}
			else if (is_array($other))
			{
				if (!isset($other['id']))		
					throw new InvalidArgumentException("other", "Parameter 'other' of index '$key' must be array that has index 'id'.");
				if (!isset($other['class']))				
					throw new InvalidArgumentException("base", "Parameter 'other' of index '$key' must be array that has index 'class'.");
					
				$storeOthers[$key] = serialize($other);
			}
		}
		
		$this->othersString = serialize($storeOthers);
	}

	/**
	* @inheritdoc
	*/
	public function getOther($key)
	{
		$others = $this->getOthers();
		if (empty($others))
			return null;
		if (isset($others[$key]))
			return $others[$key];
		else
			throw new InvalidArgumentException("key", "No other with key $key.");
	}

	/**
	* @inheritdoc
	*/
	public function addOther($other, $key)
	{
		if ($this->hasOther($key))
			throw new InvalidArgumentException("key", "Already other with key $key");

		$others = $this->getOthers();
		$others[$key] = $other;
		$this->setOthers($others);
	}

	/**
	* @inheritdoc
	*/
	public function removeOther($key)
	{
		if (!$this->hasOther($key))
			throw new InvalidArgumentException("key", "No key '$key' to remove.");
			
		$others = $this->getOthers();
		unset($others[$key]);
		$this->setOthers($others);	
	}

	/**
	* @inheritdoc
	*/
	public function hasOther($key)
	{
		$others = $this->getOthers();
		if (empty($others))
			return false;
		return isset($others[$key]);
	}
}
