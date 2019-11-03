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
 * Contextaccess matcher builder, based on class names.
 */
class MatcherBuilder implements MatcherBuilderInterface
{
    /**
     * Builds contextaccess matcher.
     * In the contextaccess configuration, if the matcher class begins with a "\" (FQ class name), it will be used as is, passing the matching configuration in the constructor.
     * Otherwise, given matching class will be relative to Eki\NRW\Mdl\Contexture\ContextAccess namespace.
     *
     * @param string $matcherIdentifier "Identifier" of the matcher to build (i.e. its FQ class name).
     * @param mixed $matchingConfiguration Configuration to pass to the matcher. Can be anything the matcher supports.
     * @param mixed $request The request to match against.
     *
     * @return \Eki\NRW\Mdl\Contexture\ContextAccess\Matcher
     */
    public function buildMatcher($matcherIdentifier, $matchingConfiguration, $request)
    {
        // If class begins with a '\' it means it's a FQ class name,
        // otherwise it is relative to this namespace.
        if ($matcherIdentifier[0] !== '\\') {
            $matcherIdentifier = __NAMESPACE__ . "\\Matcher\\$matcherIdentifier";
        }

        /** @var $matcher \Eki\NRW\Mdl\Contexture\ContextAccess\Matcher */
        $matcher = new $matcherIdentifier($matchingConfiguration);
        $matcher->setRequest($request);

        return $matcher;
    }
}
