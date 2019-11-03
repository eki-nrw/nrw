<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\Routing\Generator;

use Eki\NRW\Component\Core\MVC\Symfony\Event\RouteReferenceGenerationEvent;
use Eki\NRW\Component\Core\MVC\Symfony\MVCEvents;
use Eki\NRW\Component\Core\MVC\Symfony\RequestStackAware;
use Eki\NRW\Component\Core\MVC\Symfony\Routing\RouteReference;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class RouteReferenceGenerator implements RouteReferenceGeneratorInterface
{
    use RequestStackAware;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Generates a RouteReference, based on the given resource and associated params.
     * If $resource is null, generated route reference will be based on current request's route and parameters.
     *
     * @param mixed $resource The route name. Can be any resource supported by the different routers (e.g. Location object).
     * @param array $params Array of parameters, used to generate the final link along with $resource.
     *
     * @return \Eki\NRW\Component\Core\MVC\Symfony\Routing\RouteReference
     */
    public function generate($resource = null, array $params = array())
    {
        $request = $this->getCurrentRequest();
        if ($resource === null) {
            $resource = $request->attributes->get('_route');
            $params += $request->attributes->get('_route_params', array());
        }

        $event = new RouteReferenceGenerationEvent(new RouteReference($resource, $params), $request);
        $this->dispatcher->dispatch(MVCEvents::ROUTE_REFERENCE_GENERATION, $event);

        return $event->getRouteReference();
    }
}
