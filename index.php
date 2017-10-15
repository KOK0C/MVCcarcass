<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$user = new \App\Models\User();
$user->email = 'test@test.com';
$user->password = '6842';
$user->name = 'Mikola';
$user->insert();

//print '<pre>';
//var_dump(get_class_vars(\App\Models\User::class));
//print '</pre>';