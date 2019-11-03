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
 * Interface of ConfigurationMapper objects that need to trigger actions before and/or after looping over
 * available scopes for mapping.
 */
interface HookableConfigurationMapperInterface extends ConfigurationMapperInterface
{
    /**
     * This method is called by the ConfigurationProcessor before looping over available scopes.
     * You may here use $contextualizer->mapConfigArray().
     *
     * @see ConfigurationProcessor::mapConfig()
     * @see ContextualizerInterface::mapConfigArray()
     *
     * @param array $config Complete parsed semantic configuration
     * @param ContextualizerInterface $contextualizer
     *
     * @return mixed
     */
    public function preMap(array $config, ContextualizerInterface $contextualizer);

    /**
     * This method is called by the ConfigurationProcessor after looping over available scopes.
     * You may here use $contextualizer->mapConfigArray().
     *
     * @see ConfigurationProcessor::mapConfig()
     * @see ContextualizerInterface::mapConfigArray()
     *
     * @param array $config Complete parsed semantic configuration
     * @param ContextualizerInterface $contextualizer
     *
     * @return mixed
     */
    public function postMap(array $config, ContextualizerInterface $contextualizer);
}
