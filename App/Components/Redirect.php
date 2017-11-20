<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 20.11.2017
 * Time: 17:21
 */

namespace IhorRadchenko\App\Components;

class Redirect
{
    public static function to(string $location = '')
    {
        if ($location) {
            header('Location: ' . $location);
            exit();
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}