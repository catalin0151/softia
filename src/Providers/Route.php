<?php

namespace Softia\Challenge\CoffeeMachine\Providers;

use Softia\Challenge\CoffeeMachine\Controllers;
use Softia\Challenge\CoffeeMachine\Controllers\ClientController;

class Route
{
    private static $instance = null;
    private $controllers = [];
    private $mapping = [];

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Route();
        }
        return self::$instance;
    }

    public static function add(String $uri, String $controller, String $action) {
        $route = self::getInstance();
        if (!isset($route->controllers[$controller])) {
            $controllerWithNamespace = "\Softia\Challenge\CoffeeMachine\Controllers\\" . $controller;
            $route->controllers[$controller] = new $controllerWithNamespace();
        }
        $route->mapping[$uri] = function ($params = []) use ($route, $controller, $action) {
            return $route->controllers[$controller]->$action($params);
        };
    }

    public static function goTo(String $uri, $params = []) {
        $route = self::getInstance();
        return $route->mapping[$uri]($params);
    }
}