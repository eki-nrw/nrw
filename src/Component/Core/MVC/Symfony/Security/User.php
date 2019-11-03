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
use Eki\NRW\Component\Base\Engine\Permission\User\UserReference;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class User implements ReferenceUserInterface, EquatableInterface
{
    /**
     * @var \Eki\NRW\Component\Base\Engine\Permission\User\User
     */
    private $user;

    /**
     * @var \Eki\NRW\Component\Base\Engine\Permission\User\UserReference
     */
    private $reference;

    /**
     * @var array
     */
    private $roles;

    public function __construct(BaseUser $user = null, array $roles = array())
    {
        $this->user = $user;
        $this->reference = new UserReference($user ? $user->getUserId() : null);
        $this->roles = $roles;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array( 'ROLE_USER' );
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->getBaseUser()->passwordHash;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getBaseUser()->login;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return \Eki\NRW\Component\Base\Engine\Permission\User\UserReference
     */
    public function getBaseUserReference()
    {
        return $this->reference;
    }

    /**
     * @return \Eki\NRW\Component\Base\Engine\Permission\User\User
     */
    public function getBaseUser()
    {
        if (!$this->user instanceof BaseUser) {
            throw new \LogicException(
                'Attempts to get BaseUser before it has been set by UserProvider, BaseUser is not serialized to session'
            );
        }

        return $this->user;
    }

    /**
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\User $user
     */
    public function setBaseUser(BaseUser $user)
    {
        $this->user = $user;
        $this->reference = new UserReference($user->getUserId());
    }

    public function isEqualTo(BaseUserInterface $user)
    {
        // Check for the lighter ReferenceUserInterface first
        if ($user instanceof ReferenceUserInterface) {
            return $user->getBaseUserReference()->getUserId() === $this->reference->getUserId();
        } elseif ($user instanceof UserInterface) {
            return $user->getBaseUser()->getUserId() === $this->reference->getUserId();
        }

        return false;
    }

    public function __toString()
    {
        return $this->getBaseUser()->name;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return $this->getBaseUser()->enabled;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return $this->getBaseUser()->enabled;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->getBaseUser()->enabled;
    }

    /**
     * Make sure we don't serialize the whole API user object given it's a full fledged api content object. We set
     * (& either way refresh) the user object in \Eki\NRW\Component\Core\MVC\Symfony\Security\User\Provider->refreshUser()
     * when object wakes back up from session.
     *
     * @return array
     */
    public function __sleep()
    {
        return ['reference', 'roles'];
    }
}
