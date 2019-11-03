<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Permission\Role;

use Eki\NRW\Component\SPBase\Persistence\ValueObject;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
 * This class represents a persistence role.
 *
 * @property-read string $identifier Identifier of the role
 */
abstract class Role extends ValueObject implements ResInterface
{
	use
		ResTrait
	;

	/**
	* @var string
	*/	
	protected $identifier;
	
	/**
	* Humandable name of the role
	* 
	* @var string
	*/
	public $name;

	/**
	* @var \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Policy[];
	*/	
	public $policies = array();
}
