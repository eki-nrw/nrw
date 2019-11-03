<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Permission\Role;

use Eki\NRW\Component\Base\Permission\Role\Role as RoleInterface;
use Eki\NRW\Component\Base\Permission\Role\Policy;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
 * This class represents a role.
 *
 */
class Role implements RoleInterface
{
	use
		ResTrait
	;

    /**
     * @var string
     */
    protected $identifier;
    
    /**
	* @var Policy[]
	*/
	protected $policies;    

	public function __construct($identifier, array $policies = array())
	{
		$this->identifier = $identifier;
		
		foreach($policies as $policy)
		{
			if (!$policy instanceof Policy)
			{
				throw new InvalidArgumentException("Member of array policies must be Policy.");
			}
		}
		
		$this->policies = $policies;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getIdentifier()
	{
		return $this->identifier;
	}

	/**
	* @inheritdoc
	* 
	*/
    public function getPolicies()
    {
		return $this->policies;
	}
}
