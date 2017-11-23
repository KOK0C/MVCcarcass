<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 23.11.2017
 * Time: 18:30
 */

namespace IhorRadchenko\App\Components;

class Token
{
    public static function generate(string $name)
    {
        if (! Session::has($name)) {
            Session::set($name, bin2hex(random_bytes(32)));
        }
        return Session::get($name);
    }

    public static function check(string $name, string $value)
    {
        if (Session::has($name) && Session::get($name) === $value) {
            Session::delete($name);
            return true;
        }
        return false;
    }
}