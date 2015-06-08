<?php

namespace SilexSymfony\Routing;

use Symfony\Component\Routing\Router as SymfonyRouter;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class Router extends SymfonyRouter
{
    protected $routes;

    public function setRoutes(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function match($pathinfo)
    {
        $match = parent::match($pathinfo);

        // injects the route into the route collection, so Silex will keep its behavior
        $route = $this->getRouteFromMatch($match, $pathinfo);
        $this->routes->add($match['_route'], $route);

        return $match;
    }

    private function getRouteFromMatch($match, $pathinfo)
    {
        $options = $this->getOptions($match);

        return new Route($pathinfo, $match, $requirements = array(), $options, $host = '', $schemes = array(), $methods = array(), $condition = '');
    }

    private function getOptions($match)
    {
        $options = $match;

        if (isset($options['_controller'])) {
            unset($options['_controller']);
        }

        if (isset($options['_route'])) {
            unset($options['_route']);
        }

        if (isset($options['_before'])) {
            $options['_before_middlewares'] = $match['_before'];

            unset($options['_before']);
        }

        return $options;
    }
}
