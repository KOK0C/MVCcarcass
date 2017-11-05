<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 05.11.2017
 * Time: 13:37
 */

namespace App\Components;

class Cache
{
    public function set(string $name, $data, int $seconds): bool
    {
        $content['data'] = $data;
        $content['die_time'] = time() + $seconds;
        if (file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/tmp/cache/' . md5($name) . '.txt', serialize($content))) {
            return true;
        }
        return false;
    }

    public function get(string $name)
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . '/tmp/cache/' . md5($name) . '.txt';
        if (file_exists($file)) {
            $content = unserialize(file_get_contents($file));
            if (time() <= $content['die_time']) {
                return $content['data'];
            }
            unlink($file);
        }
        return false;
    }
}