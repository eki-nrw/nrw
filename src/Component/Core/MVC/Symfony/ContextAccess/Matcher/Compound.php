<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\Matcher;

use Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\URILexer;
use Eki\NRW\Mdl\Contexture\ContextAccess\Matcher\Compound as BaseCompound;

/**
 * 
 */
abstract class Compound extends BaseCompound implements URILexer
{
    public function analyseURI($uri)
    {
        foreach ($this->getSubMatchers() as $matcher) {
            if ($matcher instanceof URILexer) {
                $uri = $matcher->analyseURI($uri);
            }
        }

        return $uri;
    }
    
    public function analyseLink($linkUri)
    {
        foreach ($this->getSubMatchers() as $matcher) {
            if ($matcher instanceof URILexer) {
                $linkUri = $matcher->analyseLink($linkUri);
            }
        }

        return $linkUri;
    }
}
