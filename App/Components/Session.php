<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 20.11.2017
 * Time: 17:24
 */

namespace IhorRadchenko\App\Components;

use IhorRadchenko\App\Models\User;

/**
 * Class Session
 * @package IhorRadchenko\App\Components
 */
class Session
{
    public static function set(string $name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @param string $key
     * @return bool|array|string|int|User
     */
    public static function get(string $key)
    {
        return $_SESSION[$key] ?? false;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function delete(string $key): bool
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }
}