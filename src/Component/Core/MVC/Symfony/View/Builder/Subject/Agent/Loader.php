<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\View\Builder\Subject\Agent;

use Eki\NRW\Mdl\MVC\Symfony\View\Builder\Subject\Loader as LoaderInterface;
use Eki\NRW\Component\Core\MVC\EngineAware;
use Eki\NRW\Component\Core\MVC\EngineAwareInterface;

/**
 * Agent Loader implementation.
 */
class Loader extends EngineAware implements LoaderInterface, EngineAwareInterface
{
	/**
	* @inheritdoc
	* 
	*/
    public function loadSubject($subjectId)
    {
		return $this->engine->getNetworkingService()->loadAgent($subjectId);
	}

	/**
	* @inheritdoc
	* 
	*/
    public function loadEmbeddedSubject($subjectId)
    {
		return $this->engine->getNetworkingService()->loadAgent($subjectId);
	}
}
