<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 
namespace Eki\NRW\Component\Locating\Validator;

use Eki\NRW\Common\Location\CoordinateValidatorInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class DelegateCoordinateValidator implements CoordinateValidatorInterface
{
	private $validators = [];
	
	public function __construct(array $validators)
	{
		foreach($validators as $validator)
		{
			if (!$validator instanceof CoordinateValidatorInterface)
				throw new \InvalidArgumentException(
					sprintf("Validator must be instance of %s. Given %s.", CoordinateValidatorInterface::class, get_class($validator))
				);
		}
		
		$this->validators = $validator;
	}
	
	/**
	* Checks if support the given coordination type
	* 
	* @param string $type Type of coodinates
	* 
	* @return bool
	*/
	public function support($type)
	{
		foreach($this->validators as $validator)
		{
			if ($validator->support($type) === true)
				return true;
		}
		
		return false;
	}

	/**
	* Validate the given coordinate
	* 
	* @param string $key
	* @param mixed $coordinate
	* @param $type
	* 
	* @return void
	* 
	* @throws \InvalidArgumentException
	*/
	public function validate($key, $coordinate, $type)
	{
		foreach($this->validators as $validator)
		{
			if ($validator->support($type) === true)
			{
				$validator->validate($key, $coordinate, $type);
				return;
			}
		}
		
		throw new \InvalidArgumentException("No validator ò type $type.");
	}	
}
