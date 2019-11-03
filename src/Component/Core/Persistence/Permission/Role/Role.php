<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Permission\Role;

use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role as PSRole;
use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Policy as PSPolicy;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

/**
 * The final implementation of a role.
 *
 */
class Role extends PSRole
{
	public function __construct($identifier, array $policies)
	{
		parent::__construct(
			array(
				'identifier' => $identifier
			)
		);	
		
		foreach($policies as $policy)
		{
			if (!$policy instanceof Policy)
				throw new InvalidArgumentException(sprintf(
					"Policy must be instance of %s. Given %s.",
					Policy::class,
					get_class($policy)
				));
		}
		
		$this->policies = $policies;
	}
	
	public function getIdentifier()
	{
		return $this->identifier;
	}
}
