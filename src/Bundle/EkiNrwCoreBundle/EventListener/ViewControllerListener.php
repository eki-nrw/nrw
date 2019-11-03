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

use Eki\NRW\Mdl\MVC\Symfony\View\Builder\ViewBuilderRegistry;
use Eki\NRW\Mdl\MVC\Symfony\View\Event\FilterViewBuilderParametersEvent;
use Eki\NRW\Mdl\MVC\Symfony\View\ViewEvents;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ViewControllerListener implements EventSubscriberInterface
{
    /** @var \Symfony\Component\HttpKernel\Controller\ControllerResolverInterface */
    private $controllerResolver;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /** @var \Eki\NRW\Mdl\MVC\Symfony\View\Builder\ViewBuilderRegistry */
    private $viewBuilderRegistry;

    /** @var \Symfony\Component\EventDispatcher\EventDispatcher */
    private $eventDispatcher;

    public function __construct(
        ControllerResolverInterface $controllerResolver,
        ViewBuilderRegistry $viewBuilderRegistry,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        $this->controllerResolver = $controllerResolver;
        $this->viewBuilderRegistry = $viewBuilderRegistry;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return array(KernelEvents::CONTROLLER => array('getController', 10));
    }

    /**
     * Configures the View for eZ View controllers.
     *
     * @param FilterControllerEvent $event
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function getController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        if (($viewBuilder = $this->viewBuilderRegistry->getFromRegistry($request->attributes->get('_controller'))) === null) {
            return;
        }

        $parameterEvent = new FilterViewBuilderParametersEvent(clone $request);
        $this->eventDispatcher->dispatch(ViewEvents::FILTER_BUILDER_PARAMETERS, $parameterEvent);
        $view = $viewBuilder->buildView($parameterEvent->getParameters()->all());
        $request->attributes->set('view', $view);

        // View parameters are added as request attributes so that they are available as controller action parameters
        $request->attributes->add($view->getParameters());

        if (($controllerReference = $view->getControllerReference()) instanceof ControllerReference) {
            $request->attributes->set('_controller', $controllerReference->controller);
            $event->setController($this->controllerResolver->getController($request));
        }
    }
}
