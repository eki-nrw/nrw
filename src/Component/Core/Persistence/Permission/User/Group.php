<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Permission\User;

use Eki\NRW\Component\SPBase\Permission\User\Group as GroupInterface;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class Group implements GroupInterface
{
	use
		ResTrait
	;
	
	/**
	* @var \Eki\NRW\Component\SPBase\Permission\User\Group
	*/
	protected $parent;

	public function __construct(Group $parent = null)
	{
		$this->parent = $parent;
	}
	
	/**
	* Returns parent group
	* 
	* @return Group|null Null if top
	*/
	public function getParent()
	{
		return $this->parent;
	}
}
