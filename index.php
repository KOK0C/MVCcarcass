<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$categories = \App\Models\Category::findAll();

$view = new \App\View();
$view->categories = \App\Models\Category::findAll();
$view->display($_SERVER['DOCUMENT_ROOT'] . '/App/templates/layouts/header.phtml');



print '<pre>';
var_dump($view->categories);
print '</pre>';