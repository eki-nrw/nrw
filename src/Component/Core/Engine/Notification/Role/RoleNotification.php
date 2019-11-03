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

use Eki\NRW\Component\Core\Engine\Notification\EngineNotification;
use Eki\NRW\Components\Base\Permission\Role\Role;

/**
 * Base Service
 */
class RoleNotification extends EngineNotification implements RoleNotificationInterface
{
	/**
	* @var \Eki\NRW\Components\Base\Permission\Role\Role
	*/
	private $role;
	
	public function __construct(Role $role, $action, $message = '', array $parameters = array())
	{
		$this->role = $role;
		
		parent::__construct($message, $parameters + array('role_action' => $action));
	}
	
	/**
	* @inheritdoc
	*/
	public function getRole()
	{
		return $this->role;		
	}
	
	/**
	* @inheritdoc
	*/
	public function getAction()
	{
		if (isset($this->parameters['role_action']))	
			return $this->parameters['role_action'];
	}
}
