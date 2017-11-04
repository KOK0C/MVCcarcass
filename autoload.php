<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:23
 */

/**
 * @param string $className
 */
function myAutoload(string $className)
{
    $fileName = $_SERVER['DOCUMENT_ROOT'] . '/' . $className . '.php';
    if (file_exists($fileName)) {
        require_once $fileName;
    }
}

spl_autoload_register('myAutoload');

/**
 * Автозагрузка трейтов
 * @param string $className
 */
spl_autoload_register(function (string $className)
{
    $class = explode('\\', $className);
    $fileName = $_SERVER['DOCUMENT_ROOT'] . '/App/Components/traits/' . $class[count($class) - 1] . '.php';
    if (file_exists($fileName)) {
        require_once $fileName;
    }
});