<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Resource\Relationship;

use Eki\NRW\Compoenent\REA\Relationship\Custody;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class OwnerCustody extends Custody implements CustodyInterface
{
	public function __construct($subType = '')
	{
		parent::__construct('owner', $subType);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function buildRole(RoleBuilderInterface $builder, array $configs = array())
	{
		$builder
			->identifier(self::NAME)
			->policy()
				->service('resource')
				->permission('read')
				->limitation()
					->identifier('Resource')
					->value("self")
					->get()
				->get()
			->policy()
				->service('resource')
				->permission('edit')
				->limitation()
					->identifier('Resource')
					->value("self")
					->get()
				->get()
			->get()
		;
	}
}
