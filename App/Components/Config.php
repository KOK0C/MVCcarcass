<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 16.10.2017
 * Time: 10:23
 */

namespace IhorRadchenko\App\Components;
use IhorRadchenko\App\Components\Traits\Magic;
use IhorRadchenko\App\Components\Traits\Singleton;

/**
 * Class Config
 * @package App
 * @property string $domen
 * @property array $db
 */
class Config
{
    use Singleton;
    use Magic;

    private function __construct()
    {
        $configFilePath = $_SERVER['DOCUMENT_ROOT'] . '/App/config/config.php';
        if (file_exists($configFilePath)) {
            $this->_data = include_once $configFilePath;
        }
    }

    public function __set($name, $value)
    {
    }
}