<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 16.10.2017
 * Time: 10:23
 */

namespace App;

/**
 * Class Config
 * @package App
 */
class Config
{
    use Singleton;
    use Magic;

    private function __construct()
    {
        $configFilePath = $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        if (file_exists($configFilePath)) {
            $this->_data = include_once $configFilePath;
        }
    }

    public function __set($name, $value)
    {
    }
}