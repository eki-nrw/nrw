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
use Eki\NRW\Mdl\Contexture\ContextAccess\VersatileMatcherInterface;

/**
 * Contextaccess matcher that allows a combination of matchers, with a logical AND.
 */
class LogicalAnd extends Compound implements VersatileMatcherInterface
{
    const NAME = 'logicalAnd';

    public function match()
    {
        foreach ($this->config as $i => $rule) {
            foreach ($rule['matchers'] as $subMatcherClass => $matchingConfig) {
                // If at least one sub matcher doesn't match, jump to the next rule set.
                if ($this->matchersMap[$i][$subMatcherClass]->match() === false) {
                    continue 2;
                }
            }

            $this->subMatchers = $this->matchersMap[$i];

            return $rule['match'];
        }

        return false;
    }

    public function reverseMatch($contextAccessName)
    {
        foreach ($this->config as $i => $rule) {
            if ($rule['match'] === $contextAccessName) {
                $subMatchers = array();
                foreach ($this->matchersMap[$i] as $subMatcher) {
                    if (!$subMatcher instanceof VersatileMatcherInterface) {
                        return null;
                    }

                    $reverseMatcher = $subMatcher->reverseMatch($contextAccessName);
                    if (!$reverseMatcher) {
                        return null;
                    }

                    $subMatchers[] = $subMatcher;
                }

                $this->setSubMatchers($subMatchers);

                return $this;
            }
        }
    }
}
