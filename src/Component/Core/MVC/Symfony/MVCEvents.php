<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony;

final class MVCEvents
{
    /**
     * The CONTEXTACCESS event occurs after the SiteAccess matching has occurred.
     * This event gives further control on the matched SiteAccess.
     *
     * The event listener method receives a \Eki\NRW\Component\Core\MVC\Symfony\Event\PostContextAccessMatchEvent
     */
    const CONTEXTACCESS = 'eki_nrw.contextaccess';

    /**
     * CONFIG_SCOPE_CHANGE event occurs when configuration scope is changed (e.g. for content preview in a given siteaccess).
     *
     * The event listener method receives a Eki\NRW\Compponent\Core\MVC\Symfony\Event\ScopeChangeEvent instance.
     */
    const CONFIG_SCOPE_CHANGE = 'eki_nrw.config.scope_change';

    /**
     * CONFIG_SCOPE_RESTORE event occurs when original configuration scope is restored.
     * It always happens after a scope change (see CONFIG_SCOPE_CHANGE).
     *
     * The event listener method receives a Eki\NRW\Component\Core\MVC\Symfony\Event\ScopeChangeEvent instance.
     */
    const CONFIG_SCOPE_RESTORE = 'eki_nrw.config.scope_restore';
}
