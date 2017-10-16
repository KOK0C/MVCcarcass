<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$users = \App\Models\User::findAll();
$config = \App\Config::getInstance();

print '<pre>';
print $config->get('domen');
print '</pre>';