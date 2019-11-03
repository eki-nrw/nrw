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
class PersistenceObjectLoader implements ObjectLoaderInterface
{
	protected $persistenceHandler;
	
	public function __construct(PersistenceHandler $persistenceHandler)
	{
		$this->persistenceHandler = $persistenceHandler;		
	}
	
	/**
	* @inheritdoc
	*/
	public function support($argument)
	{
		return is_array($argument) and isset($argument['id']) and isset($argument['class']);
	}
	
	/**
	* @inheritdoc
	*/
	public function loadObject($argument)
	{
		if ($this->support($argument))
			throw new \UnexpectedValueException("Parameter 'argument' must be array and must have indices 'id' and 'class'");
			
		return $this->persistenceHandler->loadObject($argument['id'], array('class' => $argument['class']));
	}
}
