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

use Eki\NRW\Mdl\Contexture\Configuration\Configuration\ConfigResolver\ContextAccess\ConfigResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Contextualizer implements ContextualizerInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $namespace;

    /**
     * Name of the node under which scope based (semantic) configuration takes place.
     *
     * @var string
     */
    private $contextAccessNodeName;

    /**
     * @var array
     */
    private $availableContextAccesses;

    /**
     * @var array
     */
    private $groupsByContextAccess;

    public function __construct(
        ContainerInterface $containerBuilder,
        $namespace,
        $contextAccessNodeName,
        array $availableContextAccesses,
        array $groupsByContextAccess
    ) {
        $this->container = $containerBuilder;
        $this->namespace = $namespace;
        $this->contextAccessNodeName = $contextAccessNodeName;
        $this->availableContextAccesses = $availableContextAccesses;
        $this->groupsByContextAccess = $groupsByContextAccess;
    }

    public function setContextualParameter($parameterName, $scope, $value)
    {
        $this->container->setParameter("$this->namespace.$scope.$parameterName", $value);
    }

    public function mapSetting($id, array $config)
    {
        foreach ($config[$this->contextAccessNodeName] as $currentScope => $scopeSettings) {
            if (isset($scopeSettings[$id])) {
                $this->setContextualParameter($id, $currentScope, $scopeSettings[$id]);
            }
        }
    }

    public function mapConfigArray($id, array $config, $options = 0)
    {
        $this->mapReservedScopeArray($id, $config, ConfigResolver::SCOPE_DEFAULT);
        $this->mapReservedScopeArray($id, $config, ConfigResolver::SCOPE_GLOBAL);
        $defaultSettings = $this->getContainerParameter(
            $this->namespace . '.' . ConfigResolver::SCOPE_DEFAULT . '.' . $id,
            array()
        );
        $globalSettings = $this->getContainerParameter(
            $this->namespace . '.' . ConfigResolver::SCOPE_GLOBAL . '.' . $id,
            array()
        );

        foreach ($this->availableContextAccesses as $scope) {
            // for a siteaccess, we have to merge the default value,
            // the group value(s), the siteaccess value and the global
            // value of the settings.
            $groupsSettings = array();
            if (isset($this->groupsByContextAccess[$scope]) && is_array($this->groupsByContextAccess[$scope])) {
                $groupsSettings = $this->groupsArraySetting(
                    $this->groupsByContextAccess[$scope],
                    $id,
                    $config,
                    $options & static::MERGE_FROM_SECOND_LEVEL
                );
            }

            $scopeSettings = array();
            if (isset($config[$this->contextAccessNodeName][$scope][$id])) {
                $scopeSettings = $config[$this->contextAccessNodeName][$scope][$id];
            }

            if (empty($groupsSettings) && empty($scopeSettings)) {
                continue;
            }

            if ($options & static::MERGE_FROM_SECOND_LEVEL) {
                // array_merge() has to be used because we don't
                // know whether we have a hash or a plain array
                $keys1 = array_unique(
                    array_merge(
                        array_keys($defaultSettings),
                        array_keys($groupsSettings),
                        array_keys($scopeSettings),
                        array_keys($globalSettings)
                    )
                );
                $mergedSettings = array();
                foreach ($keys1 as $key) {
                    // Only merge if actual setting is an array.
                    // We assume default setting to be a clear reference for this.
                    // If the setting is not an array, we copy the right value, in respect to the precedence:
                    // 1. global
                    // 2. ContextAccess
                    // 3. Group
                    // 4. default
                    if (array_key_exists($key, $defaultSettings) && !is_array($defaultSettings[$key])) {
                        if (array_key_exists($key, $globalSettings)) {
                            $mergedSettings[$key] = $globalSettings[$key];
                        } elseif (array_key_exists($key, $scopeSettings)) {
                            $mergedSettings[$key] = $scopeSettings[$key];
                        } elseif (array_key_exists($key, $groupsSettings)) {
                            $mergedSettings[$key] = $groupsSettings[$key];
                        } else {
                            $mergedSettings[$key] = $defaultSettings[$key];
                        }
                    } else {
                        $mergedSettings[$key] = array_merge(
                            isset($defaultSettings[$key]) ? $defaultSettings[$key] : array(),
                            isset($groupsSettings[$key]) ? $groupsSettings[$key] : array(),
                            isset($scopeSettings[$key]) ? $scopeSettings[$key] : array(),
                            isset($globalSettings[$key]) ? $globalSettings[$key] : array()
                        );
                    }
                }
            } else {
                $mergedSettings = array_merge(
                    $defaultSettings,
                    $groupsSettings,
                    $scopeSettings,
                    $globalSettings
                );
            }

            if ($options & static::UNIQUE) {
                $mergedSettings = array_values(
                    array_unique($mergedSettings)
                );
            }

            $this->container->setParameter("$this->namespace.$scope.$id", $mergedSettings);
        }
    }

    /**
     * Returns the value under the $id in the $container. if the container does
     * not known this $id, returns $default.
     *
     * @param string $id
     * @param mixed $default
     *
     * @return mixed
     */
    protected function getContainerParameter($id, $default = null)
    {
        if ($this->container->hasParameter($id)) {
            return $this->container->getParameter($id);
        }

        return $default;
    }

    /**
     * Merges setting array for a set of groups.
     *
     * @param array $groups array of group name
     * @param string $id id of the setting array under ezpublish.<base_key>.<group_name>
     * @param array $config the full configuration array
     * @param int $options only static::MERGE_FROM_SECOND_LEVEL or static::UNIQUE are recognized
     *
     * @return array
     */
    private function groupsArraySetting(array $groups, $id, array $config, $options = 0)
    {
        $groupsSettings = array();
        sort($groups);
        foreach ($groups as $group) {
            if (isset($config[$this->contextAccessNodeName][$group][$id])) {
                if ($options & static::MERGE_FROM_SECOND_LEVEL) {
                    foreach (array_keys($config[$this->contextAccessNodeName][$group][$id]) as $key) {
                        if (!isset($groupsSettings[$key])) {
                            $groupsSettings[$key] = $config[$this->contextAccessNodeName][$group][$id][$key];
                        } else {
                            // array_merge() has to be used because we don't
                            // know whether we have a hash or a plain array
                            $groupsSettings[$key] = array_merge(
                                $groupsSettings[$key],
                                $config[$this->contextAccessNodeName][$group][$id][$key]
                            );
                        }
                    }
                } else {
                    // array_merge() has to be used because we don't
                    // know whether we have a hash or a plain array
                    $groupsSettings = array_merge(
                        $groupsSettings,
                        $config[$this->contextAccessNodeName][$group][$id]
                    );
                }
            }
        }

        return $groupsSettings;
    }

    /**
     * Ensures settings array defined in a given "reserved scope" are registered properly.
     * "Reserved scope" can typically be ConfigResolver::SCOPE_DEFAULT or ConfigResolver::SCOPE_GLOBAL.
     *
     * @param string $id
     * @param array $config
     * @param string $scope
     */
    private function mapReservedScopeArray($id, array $config, $scope)
    {
        if (
            isset($config[$this->contextAccessNodeName][$scope][$id])
            && !empty($config[$this->contextAccessNodeName][$scope][$id])
        ) {
            $key = "$this->namespace.$scope.$id";
            $value = $config[$this->contextAccessNodeName][$scope][$id];
            if ($this->container->hasParameter($key)) {
                $value = array_merge(
                    $this->container->getParameter($key),
                    $value
                );
            }
            $this->container->setParameter($key, $value);
        }
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setContextAccessNodeName($scopeNodeName)
    {
        $this->contextAccessNodeName = $scopeNodeName;
    }

    public function getContextAccessNodeName()
    {
        return $this->contextAccessNodeName;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setAvailableContextAccesses(array $availableContextAccesses)
    {
        $this->availableContextAccesses = $availableContextAccesses;
    }

    public function getAvailableContextAccesses()
    {
        return $this->availableContextAccesses;
    }

    public function setGroupsByContextAccess(array $groupsByContextAccess)
    {
        $this->groupsByContextAccess = $groupsByContextAccess;
    }

    public function getGroupsByContextAccess()
    {
        return $this->groupsByContextAccess;
    }
}
