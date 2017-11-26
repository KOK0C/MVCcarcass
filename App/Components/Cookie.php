<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 26.11.2017
 * Time: 10:52
 */

namespace IhorRadchenko\App\Components;

class Cookie
{
    public static function set(string $name, $value, int $expire = 60 * 60 * 24 * 30)
    {
        setcookie($name, $value, time() + $expire, '/');
    }

    public static function get(string $name)
    {
        return $_COOKIE[$name] ?? false;
    }

    public static function has(string $name): bool
    {
        return isset($_COOKIE[$name]) ? true : false;
    }

    public static function delete(string $name)
    {
        setcookie($name, '', time() - 1);
    }
}