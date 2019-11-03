<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing;

use Eki\NRW\Component\SPBase\Persistence\Resourcing\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\GroupHandler;

use Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Resource\Handler as ResourceHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Resourcing\Resource\Type\Handler as ResourceTypeHandler;

/**
 */
class Handler extends GroupHandler implements HandlerInterface
{
	/**
	* @var ResourceHandler
	*/
	protected $resourceHandler;

	/**
	* @var ResourceTypeHandler
	*/
	protected $resourceTypeHandler;

	/**
	* @inheritdoc
	* 
	*/
	public function resourceHandler()
	{
		if ($this->resourceHandler === null)
		{
			$this->resourceHandler = new ResourceHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('resource')
			);
		}
		
		return $this->resourceHandler;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function resourceTypeHandler()
	{
		if ($this->resourceTypeHandler === null)
		{
			$this->resourceTypeHandler = new ResourceTypeHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('resource_type')
			);
		}
		
		return $this->resourceTypeHandler;
	}
}
