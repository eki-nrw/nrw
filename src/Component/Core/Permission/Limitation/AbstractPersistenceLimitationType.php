<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Permission\Limitation\Limitation;

use Eki\NRW\Component\SPBase\Persistence\Handler as PersistenceHandler;

/**
 * LocationLimitation is a Content limitation.
 */
class AbstractPersistenceLimitationType
{
    /**
     * @var \Eki\NRW\Component\SPBase\Persistence\Handler
     */
    protected $persistence;

    /**
     * @param \Eki\NRW\Component\SPBase\Persistence\Handler $persistence
     */
    public function __construct(PersistenceHandler $persistence)
    {
        $this->persistence = $persistence;
    }
}
