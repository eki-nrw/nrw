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

use Symfony\Component\DependencyInjection\ContainerInterface;
use InvalidArgumentException;

/**
 * Processor for ContextAccess aware configuration processing.
 * Use it when you want to map ContextAccess dependent semantic configuration to internal settings, readable
 * with the ConfigResolver.
 */
class ConfigurationProcessor
{
    /**
     * Registered configuration scopes.
     *
     * @var array
     */
    protected static $availableContextAccesses = array();

    /**
     * Registered scope groups names, indexed by scope.
     *
     * @var array
     */
    protected static $groupsByContextAccess = array();

    /**
     * Name of the node under which scope based (semantic) configuration takes place.
     *
     * @var string
     */
    protected $scopeNodeName;

    /**
     * @var ContextualizerInterface
     */
    protected $contextualizer;

    public function __construct(ContainerInterface $containerBuilder, $namespace, $contextAccessNodeName = 'system')
    {
        $this->contextualizer = $this->buildContextualizer($containerBuilder, $namespace, $contextAccessNodeName);
    }

    /**
     * Injects available ContextAccesses.
     *
     * Important: Available ContextAccesses need to be set before ConfigurationProcessor to be constructed by a bundle
     * to set its configuration up.
     *
     * @param array $availableContextAccesses
     */
    public static function setAvailableContextAccesses(array $availableContextAccesses)
    {
        static::$availableContextAccesses = $availableContextAccesses;
    }

    /**
     * Injects available scope groups, indexed by scope.
     *
     * Important: Groups need to be set before ConfigurationProcessor to be constructed by a bundle
     * to set its configuration up.
     *
     * @param array $groupsByContextAccess
     */
    public static function setGroupsByContextAccess(array $groupsByContextAccess)
    {
        static::$groupsByContextAccess = $groupsByContextAccess;
    }

    /**
     * Triggers mapping process between semantic and internal configuration.
     *
     * @param array $config Parsed semantic configuration
     * @param ConfigurationMapperInterface|callable $mapper Mapper to use. Can be either an instance of ConfigurationMapper or a callable.
     *                                                      HookableConfigurationMapper can also be used. In this case, preMap()
     *                                                      and postMap() will be also called respectively before and after the mapping loop.
     *
     *                                                      If $mapper is a callable, the same arguments as defined in the signature
     *                                                      defined in ConfigurationMapper interface will be passed:
     *                                                      `array $scopeSettings, $currentScope, ContextualizerInterface $contextualizer`
     *
     * @throws \InvalidArgumentException
     */
    public function mapConfig(array $config, $mapper)
    {
        $mapperCallable = is_callable($mapper);
        if (!$mapperCallable && !$mapper instanceof ConfigurationMapperInterface) {
            throw new InvalidArgumentException('Configuration mapper must either be a callable or an instance of ConfigurationMapper.');
        }

        if ($mapper instanceof HookableConfigurationMapperInterface) {
            $mapper->preMap($config, $this->contextualizer);
        }

        $scopeNodeName = $this->contextualizer->getContextAccessNodeName();
        foreach ($config[$scopeNodeName] as $currentScope => &$scopeSettings) {
            if ($mapperCallable) {
                call_user_func_array($mapper, array(&$scopeSettings, $currentScope, $this->contextualizer));
            } else {
                $mapper->mapConfig($scopeSettings, $currentScope, $this->contextualizer);
            }
        }

        if ($mapper instanceof HookableConfigurationMapperInterface) {
            $mapper->postMap($config, $this->contextualizer);
        }
    }

    /**
     * Proxy to `Contextualizer::mapSetting()`.
     *
     * @see ContextualizerInterface::mapSetting()
     *
     * @param string $id Id of the setting to map.
     * @param array $config Full semantic configuration array for current bundle.
     */
    public function mapSetting($id, array $config)
    {
        $this->contextualizer->mapSetting($id, $config);
    }

    /**
     * Proxy to `Contextualizer::mapConfigArray()`.
     *
     * @see ContextualizerInterface::mapConfigArray()
     *
     * @param string $id Id of the setting array to map.
     * @param array $config Full semantic configuration array for current bundle.
     * @param int $options Bit mask of options (See constants of `ContextualizerInterface`)
     */
    public function mapConfigArray($id, array $config, $options = 0)
    {
        $this->contextualizer->mapConfigArray($id, $config, $options);
    }

    /**
     * Builds configuration contextualizer (I know, sounds obvious...).
     * Override this method if you want to use your own contextualizer class.
     *
     * static::$scopes and static::$groupsByScope must be injected first.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $containerBuilder
     * @param string $namespace
     * @param string $siteAccessNodeName
     *
     * @return \Eki\NRW\Bundle\CoreBundle\DependencyInjection\Configuration\ContextAccessAware\ContextualizerInterface
     */
    protected function buildContextualizer(ContainerInterface $containerBuilder, $namespace, $siteAccessNodeName)
    {
        return new Contextualizer($containerBuilder, $namespace, $siteAccessNodeName, static::$availableContextAccesses, static::$groupsByContextAccess);
    }

    /**
     * @param \Eki\NRW\Bundle\CoreBundle\DependencyInjection\Configuration\ContextAccessAware\ContextualizerInterface $contextualizer
     */
    public function setContextualizer(ContextualizerInterface $contextualizer)
    {
        $this->contextualizer = $contextualizer;
    }

    /**
     * @return \Eki\NRW\Bundle\CoreBundle\DependencyInjection\Configuration\ContextAccessAware\ContextualizerInterface
     */
    public function getContextualizer()
    {
        return $this->contextualizer;
    }
}
