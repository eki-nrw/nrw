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

/**
 * Allows to filter ContextAccess configuration before it gets processed.
 */
interface ContextAccessConfigurationFilter
{
    /**
     * Receives the contextaccess configuration array and returns it.
     *
     * @param array $siteAccessConfiguration
     *        The ContextAccess configuration array before it gets normalized and processed.
     *        Keys: groups, list, default_siteaccess.
     *        Example:
     *        ```
     *        [
     *            'list' => ['site'],
     *            'groups' => ['site_group' => ['site']],
     *            'default_siteaccess' => 'site',
     *            'match' => ['URIElement' => 1]
     *        ]
     *        ```
     *
     * @return array The modified siteaccess configuration array
     */
    public function filter(array $contextAccessConfiguration);
}
