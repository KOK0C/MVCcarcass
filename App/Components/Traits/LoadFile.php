<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 13.12.2017
 * Time: 17:58
 */

namespace IhorRadchenko\App\Components\Traits;

use IhorRadchenko\App\Components\Session;

trait LoadFile
{

    private function loadFile(array $file, string $extension)
    {
        $fileExtension = explode('/', finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file['tmp_name']))[1];
        if (! in_array($fileExtension, explode('|', $extension))) {
            Session::set('errors', ['image' => ['Некорректный формат файла']]);
            return false;
        }
        $fileName = basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $this->uploadDir . $fileName)) {
            return $fileName;
        }
        Session::set('errors', ['image' => ['Неудалось загрузить файл']]);
        return false;
    }
}