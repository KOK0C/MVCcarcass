<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/function.php';

$errorHandler = new \App\Components\ErrorHandler();

$route = \App\Components\Router::getInstance();

$logger = new \App\Components\Logger();

try {
    $route->run();
} catch (\App\Exceptions\DbException $e) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/App/templates/layouts/errors/errorDB.phtml';
    $logger->emergency($e->getMessage(), ['Exception' => get_class($e), 'File' => $e->getFile(), 'Line' => $e->getLine()]);
    die();
} catch (\App\Exceptions\Error404 $e) {
    $logger->notice($e->getMessage(), ['Exception' => get_class($e), 'File' => $e->getFile(), 'Line' => $e->getLine()]);
    $controller = new \App\Controllers\Error;
    $controller->action('page404');
}