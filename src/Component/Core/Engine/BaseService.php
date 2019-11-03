<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine;

use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\Base\Engine\PermissionResolver;
use Eki\NRW\Component\Notification\NotificatorInterface;

/**
 * Base Service
 * 
 * + Service is of main interface of the engine
 * + Service has settings portion
 * + Permission Resolver to check authorization rights
 * + Service has a notificator to notify
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
abstract class BaseService extends AbstractService
{
	/**
	* @var \Eki\NRW\Component\Base\Engine\PermissionResolver
	*/
	protected $permissionResolver;
	
	/**
	* @var \Eki\NRW\Component\Notification\NotificatorInterface
	*/
	protected $notificator;
	
	public function __construct(
		Engine $engine,
		array $settings
	)
	{
		parent::__construct($engine, $settings);

		$this->permissionResolver = $this->engine->getPermissionResolver();
//		$this->notificator = $this->getPlugins('notificator');
	}
	
	/**
	* Notify
	* 
	* @param mixed $notification
	* 
	* @return void
	* 
	* @throws
	*/
	protected function notify($subject)
	{
		if ($this->notificator)
		{
			if (null !== ($notification = $this->formatNotification($subject)))
				$this->notificator->trigger($notification);
		}
	}
	
	/**
	* Format subject to notification message
	* 
	* @param mixed $subject
	* 
	* @return mixed
	*/
	protected function formatNotification($subject)
	{
		return null;
	}
}
