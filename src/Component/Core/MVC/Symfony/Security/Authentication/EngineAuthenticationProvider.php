<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\Security\Authentication;

use Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException;
use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\Core\MVC\Symfony\Security\UserInterface as BaseUserInterface;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;

class EngineAuthenticationProvider extends DaoAuthenticationProvider
{
    /**
     * @var \Eki\NRW\Component\Base\Engine\Engine
     */
    private $engine;

    public function setEngine(Engine $engine)
    {
        $this->engine = $engine;
    }

    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        if (!$user instanceof BaseUserInterface) {
            return parent::checkAuthentication($user, $token);
        }

        // $currentUser can either be an instance of UserInterface or just the username (e.g. during form login).
        /** @var BaseUserInterface|string $currentUser */
        $currentUser = $token->getUser();
        if ($currentUser instanceof UserInterface) {
            if ($currentUser->getBaseUser()->getPasswordHash() !== $user->getBaseUser()->getPasswordHash()) {
                throw new BadCredentialsException('The credentials were changed from another session.');
            }

            $apiUser = $currentUser->getBaseUser();
        } else {
            try {
                $apiUser = $this->engine->getUserService()->loadUserByCredentials($token->getUsername(), $token->getCredentials());
            } catch (NotFoundException $e) {
                throw new BadCredentialsException('Invalid credentials', 0, $e);
            }
        }

        // Finally inject current user in the Engine
        $this->engine->getPermissionResolver()->setCurrentReference($apiUser);
    }
}
