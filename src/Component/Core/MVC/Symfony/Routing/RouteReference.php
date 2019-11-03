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

use Symfony\Component\HttpFoundation\ParameterBag;

class RouteReference
{
    /**
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    private $params;

    /**
     * @var mixed Route name or resource (e.g. Location object).
     */
    private $route;

    public function __construct($route, array $params = array())
    {
        $this->route = $route;
        $this->params = new ParameterBag($params);
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params->all();
    }

    /**
     * Sets a route parameter.
     *
     * @param string $parameterName
     * @param mixed $value
     */
    public function set($parameterName, $value)
    {
        $this->params->set($parameterName, $value);
    }

    /**
     * Returns a route parameter.
     *
     * @param string $parameterName
     * @param mixed $defaultValue
     * @param bool $deep
     *
     * @return mixed
     */
    public function get($parameterName, $defaultValue = null, $deep = false)
    {
        return $this->params->get($parameterName, $defaultValue, $deep);
    }

    public function has($parameterName)
    {
        return $this->params->has($parameterName);
    }

    /**
     * Removes a route parameter.
     *
     * @param string $parameterName
     */
    public function remove($parameterName)
    {
        $this->params->remove($parameterName);
    }
}
