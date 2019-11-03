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

use Eki\NRW\Component\REA\Relationship\Association as BaseAbstractAssociation;
//use Eki\NRW\Component\Base\Permission\RoleBuilderInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractAssociation extends BaseAbstractAssociation implements AssociationInterface
{
	/**
	* @inheritdoc
	* 
	*/
//	public function buildRole(RoleBuilderInterface $builder, array $configs = array())
//	{
		// $agent = $this->getAgent();
		// $otherAgent = $this->getOtherAgent();
		
		// $type = $this->getMainType()
		// $subType = $this->getSubType();
		
		// Role = <$type>Of
		// 	Policies:
		//	  Policy:
		//	    Limitation = "OF", <$otherAgent>       
//	}
}
