<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Permission\User;

use Eki\NRW\Component\SPBase\Persistence\ValueObject;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class Group extends ValueObject implements ResInterface
{
	use
		ResTrait
	;
	
	/**
	* The parent id of the user group
	* 
	* @var mixed
	*/
	public $parentId;
}
