<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Notification\Role;

use Eki\NRW\Component\Core\Engine\Notification\EngineNotificationInterface;

/**
 * Base Service
 */
interface RoleNotificationInterface extends EngineNotificationInterface
{
	/**
	* Get role
	* 
	* @return \Eki\NRW\Components\Base\Permission\Role\Role
	*/
	public function getRole();
	
	/**
	* Get action on role
	* 
	* @return string
	*/
	public function getAction();
}
