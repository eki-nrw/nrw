<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC;

use Eki\NRW\Component\Base\Engine\Engine;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class EngineAware implements EngineAwareInterface
{
    /**
     * @var \Eki\NRW\Component\Base\Engine\Engine
     */
    protected $engine;

    /**
     * @param \Eki\NRW\Component\Base\Engine\Engine $engine
     */
    public function setEngine(Engine $engine = null)
    {
        $this->engine = $engine;
    }
}
