<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/function.php';

$route = \App\Components\Router::getInstance();
try {
    $route->run();
} catch (\App\Exceptions\Error404 $e) {
    $controller = new \App\Controllers\Error;
    $controller->action('page404');
} catch (\App\Exceptions\DbException $e) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/App/templates/layouts/errors/errorDB.phtml';
    die();
}