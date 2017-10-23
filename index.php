<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$route = new \App\Components\Router();
$route->run();



//$pdo = new \App\DataBase();
//$news =  $pdo->query('SELECT * FROM news WHERE id=11', \App\Models\Article::class);
//var_dump($news);

//print '<pre>';
//var_dump($view->categories);
//print '</pre>';