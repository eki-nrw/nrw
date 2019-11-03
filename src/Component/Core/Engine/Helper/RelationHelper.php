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

use Eki\NRW\Component\Relating\Base\Relation\RelationshipInterface;
use Eki\NRW\Component\Relating\Base\Relation\GroupInterface;
use Eki\NRW\Common\Relations\Node;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Relation Helper
 */
class RelationHelper
{
	/**
	* Link between the object and the other object by the given relationship
	* 
	* @param RelationshipInterface $relationship
	* @param object $object
	* @param object $otherObject
	* 
	* @return RelationshipInterface
	* 
	* @throws \InvalidArgumentException
	*/
	public function linkRelationship(RelationshipInterface $relationship, $object, $otherObject)
	{
		$relationship->setNode(
			new Node(
				$this->getObjectName($object),
				$object
			)
		);

		$relationship->setOtherNode(
			new Node(
				$this->getObjectName($otherObject),
				$otherObject
			)
		);
		
		return $relationship;
	}
	
	/**
	* Unlink base object and other object from the given relationship
	* 
	* @param RelationshipInterface $relationship
	* @param object $object
	* @param object $otherObject
	* 
	* @return void
	* 
	*/
	public function unlinkRelationship(RelationshipInterface $relationship)
	{
		$relationship->setNode();
		$relationship->setOtherNode();
	}
	
	/**
	* Group between the base object and the other objects by the given group relation
	* 
	* @param GroupInterface $group
	* @param object $groupObject
	* @param object[]] $otherObjects
	* 
	* @return GroupInterface
	* 
	* @throws \InvalidArgumentException
	*/
	public function grouping(GroupInterface $group, $groupObject, array $otherObjects)
	{
		$group->setGroup(
			new Node(
				$this->getObjectNamse($groupObject), 
				$groupObject
			)
		);
		
		foreach($otherObjects as $key => $other)
		{
			$group->addMember(
				new Node(
					$this->getObjectNamse($other), 
					$other
				),
				spl_object_hash($other)
			);
		}
		
		return $group;
	}
	
	/**
	* Ungroup all group object and other objects from the given group relation
	* 
	* @param GroupInterface $group
	* 
	*/
	public function ungrouping(GroupInterface $group)
	{
		$group->setMembers();
		$group->setGroup();
	}
	
	/**
	* Add the given object to the given group as member $key
	* 
	* @param GroupInterface $group
	* @param object $object
	* @param string $key
	* 
	* @return void
	*/
	public function addToGroup(GroupInterface $group, $object, $key = null)
	{
		$group->addMember(
			new Node(
				$this->getObjectNamse($object), 
				$object
			),
			$key !== null ? $key : spl_object_hash($object)
		);
	}

	/**
	* Remove the given object from the given group
	* 
	* @param GroupInterface $group
	* @param object $object
	* 
	* @return void
	* 
	* @throws \LogicException If $object is not in group
	*/
	public function removeFromGroup(GroupInterface $group, $object)
	{
		foreach($group->getMemers() as $key => $member)
		{
			if ($member->getObject() === $object)
			{
				$group->removeMemberByKey($key);
				return;
			}
		}
		
		throw new \LogicException("No node that has matched object to remove.");
	}
	
	protected function getObjectName($object)
	{
		$accessor = PropertyAccess::createPropertyAccessor();
		try 
		{
			$valueGot = $accessor->getValue($object, 'name');
		}
		catch(\Exception $e)
		{
			$valueGot = null;
		}
		
		return $valueGot !== null ? $valueGot : '';
	}
}
