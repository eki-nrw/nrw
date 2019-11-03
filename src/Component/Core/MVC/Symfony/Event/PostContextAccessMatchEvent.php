<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Eki\NRW\Mdl\Contexture\ContextAccess\ContextAccess;

/**
 * This event is triggered after ContextAccess matching process and allows further control on it and the associated request.
 */
class PostContextAccessMatchEvent extends Event
{
    /**
     * @var \Eki\NRW\Mdl\Contexture\ContextAccess\ContextAccess
     */
    private $contextAccess;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * The request type the kernel is currently processing.  One of
     * HttpKernelInterface::MASTER_REQUEST and HttpKernelInterface::SUB_REQUEST.
     *
     * @var int
     */
    private $requestType;

    public function __construct(ContextAccess $siteAccess, Request $request, $requestType)
    {
        $this->contextAccess = $contextAccess;
        $this->request = $request;
        $this->requestType = $requestType;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns matched SiteAccess instance.
     *
     * @return \Eki\NRW\Mdl\Contexture\ContextAccess\ContextAccess
     */
    public function getContextAccess()
    {
        return $this->contextAccess;
    }

    /**
     * Returns the request type the kernel is currently processing.
     *
     * @return int  One of HttpKernelInterface::MASTER_REQUEST and
     *                  HttpKernelInterface::SUB_REQUEST
     */
    public function getRequestType()
    {
        return $this->requestType;
    }
}
