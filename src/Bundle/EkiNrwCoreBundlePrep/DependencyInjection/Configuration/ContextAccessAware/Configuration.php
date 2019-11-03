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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Base class to build scope based semantic configuration tree (aka ContextAccess aware configuration).
 * This is very helpful if you need to define specific configuration blocks which need to be repeated by scope/contexts.
 *
 * Example of scope (aka ContextAccesses) usage, "system" being the node under which scope based configuration take place.
 * Key is the context name.
 *
 * <code>
 * eki_nrw:
 *     system:
 *         eng:
 *             languages:
 *                 - eng-GB
 *
 *         fre:
 *             languages:
 *                 - fre-FR
 *                 - eng-GB
 * </code>
 */
abstract class Configuration implements ConfigurationInterface
{
    /**
     * Generates the context node under which context based configuration will be defined.
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode Node under which the generated node will be placed.
     * @param string $scopeNodeName
     *
     * @return \Symfony\Component\Config\Definition\Builder\NodeBuilder
     */
    public function generateScopeBaseNode(ArrayNodeDefinition $rootNode, $scopeNodeName)
    {
        $contextNode = $rootNode
            ->children()
                ->arrayNode($scopeNodeName)
                    ->info('System configuration. First key is always a contextaccess or contextaccess group name')
                    ->example(
                        array(
                            'my_contextaccess' => array(
                                'preferred_quote' => 'Let there be Light!',
                                'j_aime' => array('le_saucisson'),
                            ),
                            'my_contextaccess_group' => array(
                                'j_aime' => array('la_truite_a_la_vapeur'),
                            ),
                        )
                    )
                    ->useAttributeAsKey('contextaccess_name')
                    ->requiresAtLeastOneElement()
                    ->normalizeKeys(false)
                    ->prototype('array')
                        ->children();

        return $contextNode;
    }
}
