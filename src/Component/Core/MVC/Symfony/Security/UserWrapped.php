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

use Eki\NRW\Component\Base\Engine\Permission\User\User as BaseUser;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface as CoreUserInterface;
use InvalidArgumentException;

/**
 * This class represents a UserWrapped object.
 *
 * It's used when working with multiple user providers
 *
 * It has two properties:
 *     - wrappedUser: containing the originally matched user.
 *     - apiUser: containing the API User (the one from the eZ Repository )
 */
class UserWrapped implements UserInterface, EquatableInterface
{
    /**
     * @var \Symfony\Component\Security\Core\User\UserInterface
     */
    private $wrappedUser;

    /**
     * @var \Eki\NRW\Component\Base\Engine\Permission\User\User
     */
    private $baseUser;

    public function __construct(CoreUserInterface $wrappedUser, BaseUser $baseUser)
    {
        $this->setWrappedUser($wrappedUser);
        $this->baseUser = $baseUser;
    }

    public function __toString()
    {
        return $this->wrappedUser->getUsername();
    }

    public function isAccountNonExpired()
    {
        return $this->wrappedUser instanceof AdvancedUserInterface ? $this->wrappedUser->isAccountNonExpired() : true;
    }

    public function isAccountNonLocked()
    {
        return $this->wrappedUser instanceof AdvancedUserInterface ? $this->wrappedUser->isAccountNonLocked() : true;
    }

    public function isCredentialsNonExpired()
    {
        return $this->wrappedUser instanceof AdvancedUserInterface ? $this->wrappedUser->isCredentialsNonExpired() : true;
    }

    public function isEnabled()
    {
        return $this->wrappedUser instanceof AdvancedUserInterface ? $this->wrappedUser->isEnabled() : true;
    }

    /**
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\User $baseUser
     */
    public function setBaseUser(BaseUser $baseUser)
    {
        $this->baseUser = $baseUser;
    }

    /**
     * @return \Eki\NRW\Component\BaseVEngine\Permission\User\User
     */
    public function getAPIUser()
    {
        return $this->apiUser;
    }

    /**
     * @throws InvalidArgumentException If $wrappedUser is instance of self or User to avoid duplicated APIUser in session.
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $wrappedUser
     */
    public function setWrappedUser(CoreUserInterface $wrappedUser)
    {
        if ($wrappedUser instanceof self) {
            throw new InvalidArgumentException('Injecting UserWrapped to itself is not allowed to avoid recursion');
        } elseif ($wrappedUser instanceof User) {
            throw new InvalidArgumentException('Injecting User into UserWrapped causes duplication of APIUser, not wanted for session serialization');
        }

        $this->wrappedUser = $wrappedUser;
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getWrappedUser()
    {
        return $this->wrappedUser;
    }

    public function getRoles()
    {
        return $this->wrappedUser->getRoles();
    }

    public function getPassword()
    {
        return $this->wrappedUser->getPassword();
    }

    public function getSalt()
    {
        return $this->wrappedUser->getSalt();
    }

    public function getUsername()
    {
        return $this->wrappedUser->getUsername();
    }

    public function eraseCredentials()
    {
        $this->wrappedUser->eraseCredentials();
    }

    public function isEqualTo(CoreUserInterface $user)
    {
        return $this->wrappedUser instanceof EquatableInterface ? $this->wrappedUser->isEqualTo($user) : true;
    }
}
