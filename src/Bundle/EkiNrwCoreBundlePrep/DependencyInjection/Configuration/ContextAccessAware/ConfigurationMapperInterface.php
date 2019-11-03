<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Bundle\CoreBundle\DependencyInjection\Configuration\ContextAccessAware;

/**
 * ConfigurationMapper purpose is to map parsed semantic configuration for given scope
 * (ContextAccess, ContextAccess group or "global") to internal container parameters with the appropriate format.
 *
 * ConfigurationMapper needs to be passed to `ConfigurationProcessor::mapConfig()`.
 *
 * @see ConfigurationProcessor::mapConfig()
 */
interface ConfigurationMapperInterface
{
    /**
     * Does semantic config to internal container parameters mapping for $currentScope.
     *
     * This method is called by the `ConfigurationProcessor`, for each available scopes (e.g. ContextAccess, ContextAccess groups or "global").
     *
     * @param array $scopeSettings Parsed semantic configuration for current scope.
     *                             It is passed by reference, making it possible to alter it for usage after `mapConfig()` has run.
     * @param string $currentScope
     * @param ContextualizerInterface $contextualizer
     */
    public function mapConfig(array &$scopeSettings, $currentScope, ContextualizerInterface $contextualizer);
}
