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

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class CollaboratorAssociation extends AbstractAssociation
{
	const NAME = 'collaborator';
	
	public function __construct($subType = '')
	{
		parent::__construct(self::NAME, $subType);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function buildRole(RoleBuilderInterface $builder, array $configs = array())
	{
		$builder
			->identifier(= self::NAME)
			->policy()
				->service('agent')
				->permission('read')
				->limitation()
					->identifier('Agent')
					->value("sibling")
					->get()
				->get()
			->get()
		;
	}
}
