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
use Eki\NRW\Component\Core\MVC\ConfigResolverInterface;
use Eki\NRW\Component\Core\Engine\Permission\User\UserReference;
use Symfony\Component\Security\Core\Authentication\Provider\AnonymousAuthenticationProvider as BaseAnonymousProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AnonymousAuthenticationProvider extends BaseAnonymousProvider
{
    /**
     * @var \Eki\NRW\Component\Base\Engine\Engine
     */
    private $engine;

    /**
     * @var \Eki\NRW\Component\Core\MVC\ConfigResolverInterface
     */
    private $configResolver;

    public function setEngine(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function setConfigResolver(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    public function authenticate(TokenInterface $token)
    {
        $token = parent::authenticate($token);
        $this->engine->getPermissionResolver()->setCurrentReference(
        	new UserReference($this->configResolver->getParameter('anonymous_user_id'))
        );

        return $token;
    }
}
