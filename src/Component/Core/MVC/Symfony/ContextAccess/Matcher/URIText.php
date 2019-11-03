<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\Matcher;

use Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\VersatileMatcher;
use Eki\NRW\Mdl\Contexture\ContextAccess\Request;
use Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\URILexer;

class URIText extends Regex implements VersatileMatcher, URILexer
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $suffix;

    /**
     * Constructor.
     *
     * @param array $contextAccessesConfiguration ContextAccesses configuration.
     */
    public function __construct(array $contextAccessesConfiguration)
    {
        $this->prefix = isset($contextAccessesConfiguration['prefix']) ? $contextAccessesConfiguration['prefix'] : '';
        $this->suffix = isset($contextAccessesConfiguration['suffix']) ? $contextAccessesConfiguration['suffix'] : '';

        parent::__construct(
            '^(/' . preg_quote($this->prefix, '@') . '(\w+)' . preg_quote($this->suffix, '@') . ')',
            2
        );
    }

    public function getName()
    {
        return 'uri:text';
    }

    /**
	* @inheritdoc
	* 
	*/
    public function setRequest(SimplifiedRequest $request)
    {
    	if (!$request instanceof HttpRequest)
    		throw new \InvalidArgumentException(sprintf("Request must be instance of %s.", HttpRequest::class));

        if (!$this->element) {
            $this->setMatchElement($request->pathinfo);
        }

        parent::setRequest($request);
    }

    /**
     * Analyses $uri and removes the contextaccess part, if needed.
     *
     * @param string $uri The original URI
     *
     * @return string The modified URI
     */
    public function analyseURI($uri)
    {
        $uri = '/' . ltrim($uri, '/');

        return preg_replace("@$this->regex@", '', $uri);
    }

    /**
     * Analyses $linkUri when generating a link to a route, in order to have the contextaccess part back in the URI.
     *
     * @param string $linkUri
     *
     * @return string The modified link URI
     */
    public function analyseLink($linkUri)
    {
        $linkUri = '/' . ltrim($linkUri, '/');
        $contextAccessUri = "/$this->prefix" . $this->match() . $this->suffix;

        return $contextAccessUri . $linkUri;
    }

    public function reverseMatch($contextAccessName)
    {
        $this->request->setPathinfo("/{$this->prefix}{$contextAccessName}{$this->suffix}{$this->request->pathinfo}");

        return $this;
    }

	/**
	* @inheritdoc
	* 
	*/
    public function getRequest()
    {
        return $this->request;
    }
}
