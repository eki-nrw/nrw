<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Bundle\EkiNrpCoreBundle\SiteAccess;

use Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\MatcherBuilder as BaseMatcherBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Eki\NRW\Mdl\Contexture\ContextAccess\Request;
use RuntimeException;

/**
 * Contextaccess matcher builder based on services.
 */
class MatcherBuilder extends BaseMatcherBuilder
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Builds siteaccess matcher.
     * If $matchingClass begins with "@", it will be considered as a service identifier and loaded with the service container.
     *
     * @param $matchingClass
     * @param $matchingConfiguration
     * @param \Eki\NRW\Mdl\Contexture\ContextAccess\Request $request
     *
     * @return \Eki\NRW\Bundle\CoreBundle\ContextAccess\MatcherInterface
     *
     * @throws \RuntimeException
     */
    public function buildMatcher($matchingClass, $matchingConfiguration, Request $request)
    {
        if ($matchingClass[0] === '@') {
            /** @var $matcher \Eki\NRW\Bundle\EkiNrpCoreBundle\SiteAccess\Matcher */
            $matcher = $this->container->get(substr($matchingClass, 1));
            if (!$matcher instanceof Matcher) {
                throw new RuntimeException('A service based siteaccess matcher MUST implement ' . __NAMESPACE__ . '\\Matcher interface.');
            }

            $matcher->setMatchingConfiguration($matchingConfiguration);
            $matcher->setRequest($request);

            return $matcher;
        }

        return parent::buildMatcher($matchingClass, $matchingConfiguration, $request);
    }
}
