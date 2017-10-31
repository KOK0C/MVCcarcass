<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 31.10.2017
 * Time: 18:13
 */

namespace App\Components;

class TextFormat
{

    public static function format(string $text): string
    {
        $text = self::changeTag($text, 'p');
        $text = self::changeTag($text, 'b');
        return $text;
    }

    private static function changeTag(string $text, string $tagName): string
    {
        if (substr_count($text, '[' . $tagName . ']') === substr_count($text, '[/' . $tagName . ']')) {
            $text = preg_replace("~\[$tagName\]~", "<$tagName>", $text);
            $text = preg_replace("~\[/$tagName\]~", "</$tagName>", $text);
        }
        return $text;
    }
}