<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 20.11.2017
 * Time: 17:24
 */

namespace IhorRadchenko\App\Components;

class Session
{
    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function get($name, $child = '')
    {
        if ($child) {
            return $_SESSION[$name][$child] ?? false;
        }
        return $_SESSION[$name] ?? false;
    }

    public static function has($name, $child = '')
    {
        if ($child) {
            return isset($_SESSION[$name][$child]);
        }
        return isset($_SESSION[$name]);
    }

    public static function delete($name, $child = '')
    {
        if ($child && self::has($name, $child)) {
            unset($_SESSION[$name][$child]);
            return true;
        } elseif (! $child && self::has($name)) {
            unset($_SESSION[$name]);
            return true;
        }
        return false;
    }
}