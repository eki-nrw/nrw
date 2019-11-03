<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Helper;

use Eki\NRW\Component\Core\Persistence\Handler as PersistenceHandler;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class ServiceObjectLoader implements ObjectLoaderInterface
{
	protected $services;
	
	public function __construct(array $services)
	{
		$this->services = $services;		
	}
	
	/**
	* @inheritdoc
	*/
	public function support($argument)
	{
		return is_int($argument) or is_string($argument);
	}
	
	/**
	* @inheritdoc
	*/
	public function loadObject($argument)
	{
		if ($this->support($argument))
			throw new UnexpectedValueException("Parameter 'argument' must be integer or string.");

		foreach($this->services as $service)
		{
			try 
			{
				//$service
			}
			catch(\Exception $e)
			{
				
			}
		}
		
		return null;		
	}
}
