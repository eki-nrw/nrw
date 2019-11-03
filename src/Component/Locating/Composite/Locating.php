<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Locating\Composite;

use Eki\NRW\Component\Relating\Relation\Group;
use Eki\NRW\Common\Relations\Node;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class Locating extends Group implements LocatingInterface
{
	public function __construct($locatingType, $type = '', $name = '', $code = null)
	{
		parent::__construct(
			$this->composeTypes(Constants::LOCATING_DOMAIN, $locatingType, $type),
			$name, 
			$code
		);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function atLocation(LocationInterface $location)
	{
		$this->setGroup(new Node($location->getLocationName(), $location));
	}

	protected function addObj($obj)
	{
		$this->addMember(new Node('', $obj), spl_object_hash($obj));
	}

	protected function removeObj($obj)
	{
		$key = spl_object_hash($obj);
		if (!$this->hasMember($key))
			throw new \InvalidArgumentException("Object not found.");
			
		$this->removeMemberByKey($key);
	}
}
