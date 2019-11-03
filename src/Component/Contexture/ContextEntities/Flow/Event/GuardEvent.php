<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Contexture\ContextEntities\Flow\Event;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class GuardEvent extends Event
{
    private $blocked = false;

    public function isBlocked()
    {
        return $this->blocked;
    }

    public function setBlocked($blocked)
    {
        $this->blocked = (bool) $blocked;
    }
}
