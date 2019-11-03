<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Bundle\CoreBundle\ContextAccess;

use Eki\NRW\Mdl\Contexture\ContextAccess\MatcherInterface as BaseMatcherInterface;

/**
 * Interface for service based siteaccess matchers.
 */
interface MatcherInterface extends BaseMatcherInterface
{
    /**
     * Registers the matching configuration associated with the matcher.
     *
     * @param mixed $matchingConfiguration
     */
    public function setMatchingConfiguration($matchingConfiguration);
}
