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

use Eki\NRW\Component\Base\Engine\Engine;
use Symfony\Component\Security\Core\Authentication\Provider\RememberMeAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class RememberMeEngineAuthenticationProvider extends RememberMeAuthenticationProvider
{
    /**
     * @var \Eki\NRW\Component\Base\Engine\Engine
     */
    private $engine;

    public function setEngine(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        $authenticatedToken = parent::authenticate($token);
        if (empty($authenticatedToken)) {
            throw new AuthenticationException('The token is not supported by this authentication provider.');
        }

        $this->engine->getPermissionResolver()->setCurrentReference(
            $authenticatedToken->getUser()->getBaseUser()
        );

        return $authenticatedToken;
    }
}
