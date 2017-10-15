<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$user = \App\Models\User::findById(1);
print_r($user);
$user->name = 'Vera';
print_r($user);
$user->update();
//print '<pre>';
//var_dump(get_class_vars(\App\Models\User::class));
//print '</pre>';