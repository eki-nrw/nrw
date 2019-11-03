<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\Event;

use Eki\NRW\Mdl\Contexture\ContextAccess\ContextAccess;
use Symfony\Component\EventDispatcher\Event;

/**
 * This event is sent when configuration scope is changed (e.g. for subject preview in a given contextaccess).
 */
class ScopeChangeEvent extends Event
{
    /**
     * @var \Eki\NRW\Mdl\Contexture\ContextAccess\ContextAccess
     */
    private $contextAccess;

    public function __construct(ContextAccess $contextAccess)
    {
        $this->contextAccess = $contextAccess;
    }

    /**
     * @return \Eki\NRW\Mdl\Contexture\ContextAccess\ContextAccess
     */
    public function getContextAccess()
    {
        return $this->contextAccess;
    }
}
