<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$route = new \App\Components\Router();
try {
    $route->run();
} catch (\App\Exceptions\Error404 $e) {
    $controller = new \App\Controllers\Error;
    $controller->action('page404');
} catch (\App\Exceptions\DbException $e) {
    $controller = new \App\Controllers\Error;
    $controller->action('troubleDb');
}