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
    /**
     * @param string $name
     * @return string
     * @throws \Exception
     */
    public static function generate(string $name): string
    {
        if (! Session::has($name)) {
            Session::set($name, bin2hex(random_bytes(32)));
        }
        return Session::get($name);
    }

    public static function check(string $name, string $value): bool
    {
        if (Session::has($name) && hash_equals(Session::get($name), $value)) {
            Session::delete($name);
            return true;
        }
        return false;
    }
}