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

class HostElement implements VersatileMatcherInterface
{
    /**
     * @var \Eki\NRW\Mdl\Contexture\ContextAccess\Request
     */
    private $request;

    /**
     * Number of elements to take into account.
     *
     * @var int
     */
    private $elementNumber;

    /**
     * Host elements used for matching as an array.
     *
     * @var array
     */
    private $hostElements;

    /**
     * Constructor.
     *
     * @param array|int $elementNumber Number of elements to take into account.
     */
    public function __construct($elementNumber)
    {
        if (is_array($elementNumber)) {
            // DI config parser will create an array with 'value' => number
            $elementNumber = current($elementNumber);
        }

        $this->elementNumber = (int)$elementNumber;
    }

    public function __sleep()
    {
        return array('elementNumber', 'hostElements');
    }

    /**
     * Returns matching Contextaccess.
     *
     * @return string|false Contextaccess matched or false.
     */
    public function match()
    {
        $elements = $this->getHostElements();

        return isset($elements[$this->elementNumber - 1]) ? $elements[$this->elementNumber - 1] : false;
    }

    public function getName()
    {
        return 'host:element';
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

        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function reverseMatch($contextAccessName)
    {
        $hostElements = explode('.', $this->request->host);
        $elementNumber = $this->elementNumber - 1;
        if (!isset($hostElements[$elementNumber])) {
            return null;
        }

        $hostElements[$elementNumber] = $contextAccessName;
        $this->request->setHost(implode('.', $hostElements));

        return $this;
    }

    /**
     * @return array
     */
    private function getHostElements()
    {
        if (isset($this->hostElements)) {
            return $this->hostElements;
        } elseif (!isset($this->request)) {
            return array();
        }

        $elements = explode('.', $this->request->host);

        return $this->hostElements = $elements;
    }
}
