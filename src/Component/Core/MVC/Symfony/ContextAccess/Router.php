<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\ContextAccess;

use Eki\NRW\Component\Core\MVC\Symfony\ContextAccess;
use eZ\Publish\Core\MVC\Symfony\Routing\SimplifiedRequest;
use eZ\Publish\Core\MVC\Exception\InvalidContextAccessException;
use Psr\Log\LoggerInterface;
use Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\Matcher\CompoundInterface;
use InvalidArgumentException;

class Router implements ContextAccessRouterInterface, ContextAccessAware
{
    /**
     * Name of the default contextaccess.
     *
     * @var string
     */
    protected $defaultContextAccess;

    /**
     * The configuration for contextaccess matching.
     * Consists in an hash indexed by matcher type class.
     * Value is a hash where index is what to match against and value is the corresponding contextaccess name.
     *
     * Example:
     * <code>
     * array(
     *     // Using built-in URI matcher. Key is the prefix that matches the contextaccess, in the value
     *     "Map\\URI" => array(
     *         "ezdemo_site" => "ezdemo_site",
     *         "ezdemo_site_admin" => "ezdemo_site_admin",
     *     ),
     *     // Using built-in HOST matcher. Key is the hostname, value is the contextaccess name
     *     "Map\\Host" => array(
     *         "ezpublish.dev" => "ezdemo_site",
     *         "ezpublish.admin.dev" => "ezdemo_site_admin",
     *     ),
     *     // Using a custom matcher (class must begin with a '\', as a full qualified class name).
     *     // The custom matcher must implement Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\Matcher interface.
     *     "\\My\\Custom\\Matcher" => array(
     *         "something_to_match_against" => "contextaccess_name"
     *     )
     * )
     * </code>
     *
     * @var array
     */
    protected $contextAccessesConfiguration;

    /**
     * List of configured contextaccesses.
     * Contextaccess name is the key, "true" is the value.
     *
     * @var array
     */
    protected $contextAccessList;

    /**
     * @var \Eki\NRW\Component\Core\MVC\Symfony\ContextAccess
     */
    protected $contextAccess;

    protected $contextAccessClass;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\MatcherBuilderInterface
     */
    protected $matcherBuilder;

    /**
     * @var \eZ\Publish\Core\MVC\Symfony\Routing\SimplifiedRequest
     */
    protected $request;

    /**
     * Constructor.
     *
     * @param \Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\MatcherBuilderInterface $matcherBuilder
     * @param \Psr\Log\LoggerInterface $logger
     * @param string $defaultContextAccess
     * @param array $contextAccessesConfiguration
     * @param array $contextAccessList
     * @param string|null $contextAccessClass
     */
    public function __construct(MatcherBuilderInterface $matcherBuilder, LoggerInterface $logger, $defaultContextAccess, array $contextAccessesConfiguration, array $contextAccessList, $contextAccessClass = null)
    {
        $this->matcherBuilder = $matcherBuilder;
        $this->logger = $logger;
        $this->defaultContextAccess = $defaultContextAccess;
        $this->contextAccessesConfiguration = $contextAccessesConfiguration;
        $this->contextAccessList = array_fill_keys($contextAccessList, true);
        $this->contextAccessClass = $contextAccessClass ?: 'eZ\\Publish\\Core\\MVC\\Symfony\\ContextAccess';
        $this->request = new SimplifiedRequest();
    }

    /**
     * @return \eZ\Publish\Core\MVC\Symfony\Routing\SimplifiedRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Performs ContextAccess matching given the $request.
     *
     * @param \eZ\Publish\Core\MVC\Symfony\Routing\SimplifiedRequest $request
     *
     * @throws \eZ\Publish\Core\MVC\Exception\InvalidContextAccessException
     *
     * @return \Eki\NRW\Component\Core\MVC\Symfony\ContextAccess
     */
    public function match(SimplifiedRequest $request)
    {
        $this->request = $request;

        if (isset($this->contextAccess)) {
            return $this->contextAccess;
        }

        $contextAccessClass = $this->contextAccessClass;
        $this->contextAccess = new $contextAccessClass();

        // Request header always have precedence
        // Note: request headers are always in lower cased.
        if (!empty($request->headers['x-contextaccess'])) {
            $contextaccessName = $request->headers['x-contextaccess'][0];
            if (!isset($this->contextAccessList[$contextaccessName])) {
                unset($this->contextAccess);
                throw new InvalidContextAccessException($contextaccessName, array_keys($this->contextAccessList), 'X-Contextaccess request header');
            }

            $this->contextAccess->name = $contextaccessName;
            $this->contextAccess->matchingType = 'header';

            return $this->contextAccess;
        }

        // Then check environment variable
        $contextaccessEnvName = getenv('EZPUBLISH_SITEACCESS');
        if ($contextaccessEnvName !== false) {
            if (!isset($this->contextAccessList[$contextaccessEnvName])) {
                unset($this->contextAccess);
                throw new InvalidContextAccessException($contextaccessEnvName, array_keys($this->contextAccessList), 'EZPUBLISH_SITEACCESS Environment variable');
            }

            $this->contextAccess->name = $contextaccessEnvName;
            $this->contextAccess->matchingType = 'env';

            return $this->contextAccess;
        }

        return $this->doMatch($request);
    }

    /**
     * Returns the ContextAccess object matched against $request and the contextaccess configuration.
     * If nothing could be matched, the default contextaccess is returned, with "default" as matching type.
     *
     * @param \eZ\Publish\Core\MVC\Symfony\Routing\SimplifiedRequest $request
     *
     * @return \Eki\NRW\Component\Core\MVC\Symfony\ContextAccess
     */
    private function doMatch(SimplifiedRequest $request)
    {
        foreach ($this->contextAccessesConfiguration as $matchingClass => $matchingConfiguration) {
            $matcher = $this->matcherBuilder->buildMatcher($matchingClass, $matchingConfiguration, $request);
            if ($matcher instanceof CompoundInterface) {
                $matcher->setMatcherBuilder($this->matcherBuilder);
            }

            if (($contextaccessName = $matcher->match()) !== false) {
                if (isset($this->contextAccessList[$contextaccessName])) {
                    $this->contextAccess->name = $contextaccessName;
                    $this->contextAccess->matchingType = $matcher->getName();
                    $this->contextAccess->matcher = $matcher;

                    return $this->contextAccess;
                }
            }
        }

        $this->logger->notice('Contextaccess not matched against configuration, returning default contextaccess.');
        $this->contextAccess->name = $this->defaultContextAccess;
        $this->contextAccess->matchingType = 'default';

        return $this->contextAccess;
    }

    /**
     * Matches a ContextAccess by name.
     * Returns corresponding ContextAccess object, according to configuration, with corresponding matcher.
     * Returns null if no matcher can be found (e.g. non versatile).
     *
     * @param string $contextAccessName
     *
     * @throws \InvalidArgumentException If $contextAccessName is invalid (i.e. not present in configured list).
     *
     * @return \Eki\NRW\Component\Core\MVC\Symfony\ContextAccess|null
     */
    public function matchByName($contextAccessName)
    {
        if (!isset($this->contextAccessList[$contextAccessName])) {
            throw new InvalidArgumentException("Invalid ContextAccess name provided for reverse matching: $contextAccessName");
        }

        $request = clone $this->request;
        // Be sure to have a clean pathinfo, without ContextAccess part in it.
        if ($this->contextAccess && $this->contextAccess->matcher instanceof URILexer) {
            $request->setPathinfo($this->contextAccess->matcher->analyseURI($request->pathinfo));
        }

        $contextAccessClass = $this->contextAccessClass;
        foreach ($this->contextAccessesConfiguration as $matchingClass => $matchingConfiguration) {
            $matcher = $this->matcherBuilder->buildMatcher($matchingClass, $matchingConfiguration, $request);
            if (!$matcher instanceof VersatileMatcher) {
                continue;
            }

            if ($matcher instanceof CompoundInterface) {
                $matcher->setMatcherBuilder($this->matcherBuilder);
            }

            $reverseMatcher = $matcher->reverseMatch($contextAccessName);
            if (!$reverseMatcher instanceof Matcher) {
                continue;
            }

            /** @var \Eki\NRW\Component\Core\MVC\Symfony\ContextAccess $contextAccess */
            $contextAccess = new $contextAccessClass();
            $contextAccess->name = $contextAccessName;
            $contextAccess->matcher = $reverseMatcher;
            $contextAccess->matchingType = $reverseMatcher->getName();

            return $contextAccess;
        }

        // No VersatileMatcher configured for $contextAccessName.
        $this->logger->notice("Contextaccess '$contextAccessName' could not be reverse-matched against configuration. No VersatileMatcher found. Returning default ContextAccess.");

        return new $contextAccessClass($this->defaultContextAccess, 'default');
    }

    /**
     * @return \Eki\NRW\Component\Core\MVC\Symfony\ContextAccess|null
     */
    public function getContextAccess()
    {
        return $this->contextAccess;
    }

    /**
     * @param \Eki\NRW\Component\Core\MVC\Symfony\ContextAccess $contextAccess
     */
    public function setContextAccess(ContextAccess $contextAccess = null)
    {
        $this->contextAccess = $contextAccess;
    }
}
