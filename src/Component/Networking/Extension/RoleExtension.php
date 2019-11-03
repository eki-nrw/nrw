<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Extension;

use Eki\NRW\Common\Extension\ExtensionInterface;
use Eki\NRW\Common\Extension\ExtensionPositions;
use Eki\NRW\Component\REA\Relationship\AssociationInterface;
use Eki\NRW\Component\Base\Permission\Role\BuildRoleInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class RoleExtension implements ExtensionInterface
{
	/**
	* @inheritdoc
	* 
	*/
	public function getExtensionName()
	{
		return 'networking_role';
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function support($subject, $position = null)
	{
		if (!$subject instanceof AssociationInterface)
			return false;

		if (!$subject instanceof BuildRoleInterface)
			return false;
			
		if ($position !== ExtensionPositions::POS_BUILD)
			return false;
			
		return true;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function apply($subject, $position, array $contexts = [])
	{
		if (!$this->support($subject, $position))
			throw new \InvalidArgumentException(sprintf("Don't support subject.");
			
		//....
		//$subject->buildRole(RoleBuilderInterface $builder, array $configs = array());
	}
}
