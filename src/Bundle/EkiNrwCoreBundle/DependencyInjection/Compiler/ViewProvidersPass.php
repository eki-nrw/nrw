<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Bundle\EkiNrwCoreBundle\DependencyInjection\Compiler;

use LogicException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers services tagged as eki_nrw.view_provider into the view_provider registry.
 */
class ViewProvidersPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $rawViewProviders = [];
        foreach ($container->findTaggedServiceIds('eki_nrw.view_provider') as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                // Priority range is between -255 (the lowest) and 255 (the highest)
                $priority = isset($attributes['priority']) ? max(min((int)$attributes['priority'], 255), -255) : 0;

                if (!isset($attributes['type'])) {
                    throw new LogicException("Missing mandatory attribute 'type' for eki_nrw.view_provider tag");
                }
                $type = $attributes['type'];

                $rawViewProviders[$type][$priority][] = new Reference($serviceId);
            }
        }

        $viewProviders = [];
        foreach ($rawViewProviders as $type => $viewProvidersPerPriority) {
            krsort($viewProvidersPerPriority);
            foreach ($viewProvidersPerPriority as $priorityViewProviders) {
                if (!isset($viewProviders[$type])) {
                    $viewProviders[$type] = [];
                }
                $viewProviders[$type] = array_merge($viewProviders[$type], $priorityViewProviders);
            }
        }

        if ($container->hasDefinition('eki_nrw.view_provider.registry')) {
            $container->getDefinition('eki_nrw.view_provider.registry')->addMethodCall(
                'setViewProviders',
                [$viewProviders]
            );
        }

        $flattenedViewProviders = [];
        foreach ($viewProviders as $type => $typeViewProviders) {
            foreach ($typeViewProviders as $typeViewProvider) {
                $flattenedViewProviders[] = $typeViewProvider;
            }
        }

        if ($container->hasDefinition('eki_nrw.config_scope_listener')) {
            $container->getDefinition('eki_nrw.config_scope_listener')->addMethodCall(
                'setViewProviders',
                [$flattenedViewProviders]
            );
        }
    }
}
