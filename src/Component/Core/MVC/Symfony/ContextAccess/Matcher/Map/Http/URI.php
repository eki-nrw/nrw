<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\Matcher\Map\Http;

use Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\Matcher\Map\Http\URI as BaseMap;
use Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\URILexer;

class URI extends BaseMap implements URILexer
{
    /**
     * Fixes up $uri to remove the contextaccess part, if needed.
     *
     * @param string $uri The original URI
     *
     * @return string
     */
    public function analyseURI($uri)
    {
        if (($contextaccessPart = "/$this->key") === $uri) {
            return '/';
        }

        if (mb_strpos($uri, $contextaccessPart) === 0) {
            return mb_substr($uri, mb_strlen($contextaccessPart));
        }

        return $uri;
    }

    /**
     * Analyses $linkUri when generating a link to a route, in order to have the contextaccess part back in the URI.
     *
     * @param string $linkUri
     *
     * @return string The modified link URI
     */
    public function analyseLink($linkUri)
    {
        // Joining slash between uriElements and actual linkUri must be present, except if $linkUri is empty or is just the slash root.
        $joiningSlash = empty($linkUri) || ($linkUri === '/') ? '' : '/';
        $linkUri = ltrim($linkUri, '/');
        // Removing query string to analyse as ContextAccess might be in it.
        $qsPos = strpos($linkUri, '?');
        $queryString = '';
        if ($qsPos !== false) {
            $queryString = substr($linkUri, $qsPos);
            $linkUri = substr($linkUri, 0, $qsPos);
        }

        return "/{$this->key}{$joiningSlash}{$linkUri}{$queryString}";
    }
}
