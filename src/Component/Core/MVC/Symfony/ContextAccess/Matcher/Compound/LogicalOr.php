<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\Matcher\Compound;

use Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\Matcher\Compound;
use Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\VersatileMatcherInterface;

/**
 * Contextaccess matcher that allows a combination of matchers, with a logical OR.
 */
class LogicalOr extends Compound
{
    const NAME = 'logicalOr';

    public function match()
    {
        foreach ($this->config as $i => $rule) {
            foreach ($rule['matchers'] as $subMatcherClass => $matchingConfig) {
                if ($this->matchersMap[$i][$subMatcherClass]->match()) {
                    $this->subMatchers = $this->matchersMap[$i];

                    return $rule['match'];
                }
            }
        }

        return false;
    }

    public function reverseMatch($contextAccessName)
    {
        foreach ($this->config as $i => $rule) {
            if ($rule['match'] === $contextAccessName) {
                foreach ($this->matchersMap[$i] as $subMatcher) {
                    if (!$subMatcher instanceof VersatileMatcher) {
                        continue;
                    }

                    $reverseMatcher = $subMatcher->reverseMatch($contextAccessName);
                    if (!$reverseMatcher) {
                        continue;
                    }

                    $this->setSubMatchers(array($subMatcher));

                    return $this;
                }
            }
        }
    }
}
