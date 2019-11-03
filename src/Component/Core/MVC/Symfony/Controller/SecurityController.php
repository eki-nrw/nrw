<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\Controller;

use Eki\NRW\Component\Core\MVC\ConfigResolverInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Templating\EngineInterface;

class SecurityController
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    protected $templateEngine;

    /**
     * @var \Eki\NRW\Component\Core\MVC\ConfigResolverInterface
     */
    protected $configResolver;

    /**
     * @var \Symfony\Component\Security\Http\Authentication\AuthenticationUtils
     */
    protected $authenticationUtils;

    public function __construct(EngineInterface $templateEngine, ConfigResolverInterface $configResolver, AuthenticationUtils $authenticationUtils)
    {
        $this->templateEngine = $templateEngine;
        $this->configResolver = $configResolver;
        $this->authenticationUtils = $authenticationUtils;
    }

    public function loginAction()
    {
        return new Response(
            $this->templateEngine->render(
                $this->configResolver->getParameter('security.login_template'),
                array(
                    'last_username' => $this->authenticationUtils->getLastUsername(),
                    'error' => $this->authenticationUtils->getLastAuthenticationError(),
                    'layout' => $this->configResolver->getParameter('security.base_layout'),
                )
            )
        );
    }
}
