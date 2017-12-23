<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 30.10.2017
 * Time: 19:27
 */

/**
 * Многобайтовый аналог ф-ции ucfirst
 * @param string $string
 * @param string $encoding
 * @return string
 */
function mb_ucfirst(string $string, string $encoding = 'UTF-8'): string
{
    return mb_strtoupper(mb_substr($string, 0, 1, $encoding)) .
        mb_substr($string, 1, mb_strlen($string, $encoding),$encoding);
}

/**
 * Многобайтовый аналог ф-ции ucwords
 * @param string $string
 * @param string $encoding
 * @return string
 */
function mb_ucwords(string $string, string $encoding = 'UTF-8'): string
{
    $string = explode(' ', $string);
    foreach ($string as &$str) {
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding)) .
            mb_substr($str, 1, mb_strlen($str, $encoding),$encoding);
    }
    $string = implode(' ', $string);
    return $string;
}