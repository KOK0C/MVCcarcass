<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 21.10.2017
 * Time: 15:54
 */

namespace App\Components;

use App\Controllers\Error;
use App\Exceptions\Db;
use App\Exceptions\Error404;

class Router
{
    /**
     * Массив с маршрутами
     * @var array
     */
    private $routes;

    /**
     * Массив с контроллером и экшеном
     * @var array
     */
    private $route;

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

    private function matchRoutes()
    {
        $uri = $this->getUri();
        foreach ($this->routes as $uriPattern => $route) {
            if (preg_match("~$uriPattern~", $uri, $matches)) {
                $this->route = $route;
                if (isset($matches[1])) {
                    $this->route['argument'] = $matches[1];
                }
                return true;
            }
        }
        return false;
    }

    public function run()
    {
        if ($this->matchRoutes()) {
            $controllerName = 'App\Controllers\\' . $this->route['controller'];
            $controller = new $controllerName;
            try {
                $controller->action($this->route['action'], $this->route['argument'] ?? null);
            } catch (Error404 $e) {
                $controller = new \App\Controllers\Error;
                $controller->action('page404');
            }
        } else {
            $controller = new \App\Controllers\Error;
            $controller->action('page404');
        }
    }
}