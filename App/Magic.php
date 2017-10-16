<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 16.10.2017
 * Time: 16:19
 */

namespace App;


trait Magic
{
    private $_data = [];

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        } else {
            return false;
        }
    }

    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }
}