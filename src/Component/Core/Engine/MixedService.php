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
use Eki\NRW\Component\Base\Persistence\Handler as PersistenceHandler;
use Eki\NRW\Component\Notification\NotificatorInterface;

/**
 * Mixed Service
 * 
 * + Service is of main interface of the engine
 * + Service has settings portion
 * + Permission Resolver to check authorization rights
 * + Service has a notificator to notify
 * + Has Persistence Handler to get some compact persistence handlers
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
abstract class MixedService extends BaseService
{
	/**
	* @var \Eki\NRW\Component\Base\Persistence\Handler
	*/
	protected $persistenceHandler;
	
	public function __construct(
		Engine $engine,
		array $settings,
		PersistenceHandler $persistenceHandler
	)
	{
		parent::__construct($engine, $settings);

		$this->persistenceHandler = $persistenceHandler;
	}
}
