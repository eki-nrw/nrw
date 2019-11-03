<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base;

/**
 * Container interface.
 *
 * Starting point for getting all Public API's
 */
interface Container
{
    /**
     * Get engine object.
     *
     * Public API for
     *
     * @return \Eki\NRW\Component\Base\Engine\EngineInterface
     */
    public function getEngine();
}
