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

use Eki\NRW\Component\Core\MVC\Symfony\ContextAccess\VersatileMatcherInterface;
use Eki\NRW\Mdl\Contexture\ContextAccess\Request;

class HostText extends Regex implements VersatileMatcherInterface
{
    private $prefix;

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
            '^' . preg_quote($this->prefix, '@') . "(\w+)" . preg_quote($this->suffix, '@') . '$',
            1
        );
    }

    public function getName()
    {
        return 'host:text';
    }

    /**
     * Injects the request object to match against.
     *
     * @param \Eki\NRW\Mdl\Contexture\ContextAccess\Request $request
     */
    public function setRequest(Request $request)
    {
    	if (!$request instanceof HttpRequest)
    		throw new \InvalidArgumentException(sprintf("Request must be instance of %s.", HttpRequest::class));

        if (!$this->element) {
            $this->setMatchElement($request->host);
        }

        parent::setRequest($request);
    }

    public function reverseMatch($contextAccessName)
    {
        $this->request->setHost($this->prefix . $contextAccessName . $this->suffix);

        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }
}
