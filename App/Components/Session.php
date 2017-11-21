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

    public static function get($parent, $child = '')
    {
        if ($child) {
            return $_SESSION[$parent][$child] ?? false;
        }
        return $_SESSION[$parent] ?? false;
    }

    public static function has($parent, $child = '')
    {
        if ($child) {
            return isset($_SESSION[$parent][$child]);
        }
        return isset($_SESSION[$parent]);
    }

    public static function delete($parent, $child = '')
    {
        if ($child && self::has($parent, $child)) {
            unset($_SESSION[$parent][$child]);
            return true;
        } elseif (! $child && self::has($parent)) {
            unset($_SESSION[$parent]);
            return true;
        }
        return false;
    }

    public static function message($parent, $child)
    {
        if (self::has($parent, $child)) {
            $msg = self::get($parent, $child);
            self::delete($parent, $child);
            return $msg;
        }
        return '';
    }
}