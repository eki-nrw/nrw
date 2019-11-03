<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Agent\Relationship;

use Eki\NRW\Component\Base\Permission\Role\RoleBuilderInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ChildAssociation extends AbstractAssociation
{
	public function __construct($subType = '')
	{
		parent::__construct('child', $subType);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function buildRole(RoleBuilderInterface $builder, array $configs = array())
	{
		$builder
			->identifier('child')
			->policy()
				->service('agent')
				->permission('read')
				->limitation()
					->identifier('Agent')
					->value("child")
					->get()
				->get()
			->get()
		;
	}
}
