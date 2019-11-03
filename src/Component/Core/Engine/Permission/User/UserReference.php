<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Permission\User;

use Eki\NRW\Component\Base\Engine\Permission\User\UserReference as BaseUserReference;

/**
 * This class represents a user reference for use in sessions and Repository.
 *
 * @internal Meant for internal use by Engine, type hint against API object instead.
 */
class UserReference implements BaseUserReference
{
    /**
     * @var mixed
     */
    private $userId;

    /**
     * @param mixed $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * The User id of the User this reference represent.
     *
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
