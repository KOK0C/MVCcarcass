<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 13.12.2017
 * Time: 17:58
 */

namespace IhorRadchenko\App\Components\Traits;

use IhorRadchenko\App\Components\Session;

trait File
{
    private function loadFile(array $file, string $extension, string $path)
    {
        if (!in_array($this->getFileExtension($file), explode('|', $extension))) {
            Session::set('errors', ['image' => ['Некорректный формат файла']]);
            return false;
        }
        $fileName = $this->getFileName($file);
        if (move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $path . $fileName)) {
            return $fileName;
        }
        Session::set('errors', ['image' => ['Неудалось загрузить файл']]);
        return false;
    }

    private function getFileExtension(array $file): string
    {
        return explode('/', finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file['tmp_name']))[1];
    }

    private function getFileName(array $file): string
    {
        return preg_replace('/^(.*)\.([a-z])$/i', md5('$1') . '.$2', basename($file['name']));
    }

    private function deleteFile(string $fileName)
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . $fileName;
        return file_exists($file) && unlink($file) ? true : false;
    }

    private function getImages(string $text)
    {
        preg_match_all('~<img.*src=(.*?)/?>~', $text, $matches);
        return array_map(function ($value) {
            return trim($value, '"');
        }, $matches[1]);
    }
}