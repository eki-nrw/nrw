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

use Symfony\Component\HttpFoundation\Request;

/**
 * This interface is to be implemented by authenticator classes.
 * Authenticators are meant to be used to run authentication programmatically, i.e. outside the firewall context.
 */
interface AuthenticatorInterface
{
    /**
     * Runs authentication against provided request and returns the authenticated security token.
     *
     * This method typically does:
     *  - The authentication by itself (i.e. matching a user)
     *  - User type checks (e.g. check user activation)
     *  - Inject authenticated token in the SecurityContext
     *  - (optional) Trigger SecurityEvents::INTERACTIVE_LOGIN event
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     *
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException If any authentication issue occured.
     */
    public function authenticate(Request $request);

    /**
     * Performs logout by running configured logout handlers.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logout(Request $request);
}
