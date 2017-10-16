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

    private $data = [];

    private function __construct()
    {
        $configFilePath = $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        if(file_exists($configFilePath)) {
            $this->data = include_once $configFilePath;
        }
    }

    /**
     * @param $key string
     * @return mixed|null
     */
    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }
}