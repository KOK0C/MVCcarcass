<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 21.10.2017
 * Time: 15:54
 */

namespace App\Components;

class Router
{
    private $routes;

    public function __construct()
    {
        $routesPath = $_SERVER['DOCUMENT_ROOT'] . '/App/config/routes.php';
        if (file_exists($routesPath)) {
            $this->routes = include_once $routesPath;
        }
    }

    private function getUri()
    {
        return ltrim($_SERVER['REQUEST_URI'], '/');
    }

    public function run()
    {
        $uri = $this->getUri();
        if (array_key_exists($uri, $this->routes)) {
            $controllerAction = $this->routes[$uri];
            $controllerAction = explode('/', $controllerAction);
            $controllerName = $controllerAction[0];
            $actionName = $controllerAction[1];
        } else {
            $controllerName = 'Main';
            $actionName = 'index';
        }
        $controllerName = 'App\Controllers\\' . $controllerName;
        $controller = new $controllerName;
        $controller->action($actionName);
    }
}