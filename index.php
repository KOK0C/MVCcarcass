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
$view->display( '/App/templates/layouts/header.phtml');

$mainView = new \App\View();
$mainView->news = \App\Models\Article::findAll();
$mainView->display('/App/templates/main_page.phtml');

$sideBar = new \App\View();
$sideBar->brands = \App\Models\Brands::findAll();
$sideBar->display('/App/templates/layouts/sidebar.phtml');

include $_SERVER['DOCUMENT_ROOT'] . '/App/templates/layouts/footer.phtml';

//print '<pre>';
//var_dump($view->categories);
//print '</pre>';