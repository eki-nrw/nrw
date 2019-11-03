<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\ContextAccess;

/**
 * Interface for ContextAccess matchers that need to alter the URI after matching.
 * This is useful when you have the contextaccess in the URI like "/<contextaccessName>/my/awesome/uri".
 */
interface URILexer
{
    /**
     * Analyses $uri and removes the contextaccess part, if needed.
     *
     * @param string $uri The original URI
     *
     * @return string The modified URI
     */
    public function analyseURI($uri);

    /**
     * Analyses $linkUri when generating a link to a route, in order to have the contextaccess part back in the URI.
     *
     * @param string $linkUri
     *
     * @return string The modified link URI
     */
    public function analyseLink($linkUri);
}
