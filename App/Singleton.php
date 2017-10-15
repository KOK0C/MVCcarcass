<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 15.10.2017
 * Time: 10:49
 */

namespace App;

trait Singleton
{
    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }
}