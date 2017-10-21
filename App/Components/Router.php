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
        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $uri)) {
                $segments = explode('/', $path);
                $controllerName = 'App\Controllers\\' . array_shift($segments);
                $actionName = array_shift($segments);
                $controller = new $controllerName;
                $controller->action($actionName);
                break;
            }
        }
    }
}