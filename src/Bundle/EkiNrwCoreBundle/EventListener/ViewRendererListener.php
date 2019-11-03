<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Bundle\CoreBundle\EventListener;

use Eki\NRW\Mdl\MVC\Symfony\View\Renderer as ViewRenderer;
use Eki\NRW\Mdl\MVC\Symfony\View\View;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ViewRendererListener implements EventSubscriberInterface
{
    /** @var \Eki\NRW\Mdl\MVC\Symfony\View\Renderer */
    private $viewRenderer;

    public function __construct(ViewRenderer $viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::VIEW => 'renderView'];
    }

    public function renderView(GetResponseForControllerResultEvent $event)
    {
        if (!($view = $event->getControllerResult()) instanceof View) {
            return;
        }

        if (!($response = $view->getResponse()) instanceof Response) {
            $response = new Response();
        }

        $response->setContent($this->viewRenderer->render($view));

        $event->setResponse($response);
    }
}
