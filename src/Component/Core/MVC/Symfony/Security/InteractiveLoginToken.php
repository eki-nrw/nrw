<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\Security;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * This token is used when a user has been matched by a foreign user provider.
 * It is injected in SecurityContext to replace the original token as this one holds a new user.
 */
class InteractiveLoginToken extends UsernamePasswordToken
{
    /**
     * @var string
     */
    private $originalTokenType;

    public function __construct(UserInterface $user, $originalTokenType, $credentials, $providerKey, array $roles = array())
    {
        parent::__construct($user, $credentials, $providerKey, $roles);
        $this->originalTokenType = $originalTokenType;
    }

    /**
     * @return string
     */
    public function getOriginalTokenType()
    {
        return $this->originalTokenType;
    }

    public function serialize()
    {
        return serialize(array($this->originalTokenType, parent::serialize()));
    }

    public function unserialize($serialized)
    {
        list($this->originalTokenType, $parentStr) = unserialize($serialized);
        parent::unserialize($parentStr);
    }
}
