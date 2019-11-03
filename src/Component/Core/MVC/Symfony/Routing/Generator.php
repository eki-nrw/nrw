<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\Routing;

use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * Base class for eZ Publish Url generation.
 */
abstract class Generator
{
    /**
     * @var \Symfony\Component\Routing\RequestContext
     */
    protected $requestContext;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     */
    public function setRequestContext(RequestContext $requestContext)
    {
        $this->requestContext = $requestContext;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Triggers URL generation for $urlResource and $parameters.
     *
     * @param mixed $urlResource Type can be anything, depending on the context. It's up to the router to pass the appropriate value to the implementor.
     * @param array $parameters Arbitrary hash of parameters to generate a link.
     * @param int $referenceType The type of reference to be generated (one of the constants)
     *
     * @return string
     */
    public function generate($urlResource, array $parameters, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $requestContext = $this->requestContext;

        $url = $this->doGenerate($urlResource, $parameters);

        $url = $requestContext->getBaseUrl() . $url;

        if ($referenceType === UrlGeneratorInterface::ABSOLUTE_URL) {
            $url = $this->generateAbsoluteUrl($url, $requestContext);
        }

        return $url;
    }

    /**
     * Generates the URL from $urlResource and $parameters.
     *
     * @param mixed $urlResource
     * @param array $parameters
     *
     * @return string
     */
    abstract public function doGenerate($urlResource, array $parameters);

    /**
     * Generates an absolute URL from $uri and the request context.
     *
     * @param string $uri
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return string
     */
    protected function generateAbsoluteUrl($uri, RequestContext $requestContext)
    {
        $scheme = $requestContext->getScheme();
        $port = '';
        if ($scheme === 'http' && $requestContext->getHttpPort() != 80) {
            $port = ':' . $requestContext->getHttpPort();
        } elseif ($scheme === 'https' && $requestContext->getHttpsPort() != 443) {
            $port = ':' . $requestContext->getHttpsPort();
        }

        return $scheme . '://' . $requestContext->getHost() . $port . $uri;
    }

    /**
     * Merges context from $simplifiedRequest into a clone of the current context.
     *
     * @param SimplifiedRequest $simplifiedRequest
     *
     * @return RequestContext
     */
    private function getContextBySimplifiedRequest(SimplifiedRequest $simplifiedRequest)
    {
        $context = clone $this->requestContext;
        if ($simplifiedRequest->scheme) {
            $context->setScheme($simplifiedRequest->scheme);
        }

        if ($simplifiedRequest->port) {
            $context->setHttpPort($simplifiedRequest->port);
        }

        if ($simplifiedRequest->host) {
            $context->setHost($simplifiedRequest->host);
        }

        if ($simplifiedRequest->pathinfo) {
            $context->setPathInfo($simplifiedRequest->pathinfo);
        }

        return $context;
    }
}
