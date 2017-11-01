<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 31.10.2017
 * Time: 18:13
 */

namespace App\Components;

use App\Models\Photo;

class TextFormat
{

    public static function format(string $text, int $id): string
    {
        $text = self::changeTag($text, 'p');
        $text = self::changeTag($text, 'b');
        $text = self::createPhoto($text, $id);
        return $text;
    }

    private static function changeTag(string $text, string $tagName): string
    {
        return preg_replace(
            "~(.*)\[$tagName\](.*)\[/$tagName\](.*)~",
            "$1<$tagName>$2</$tagName>$3",
            $text
        );
    }

    private static function createPhoto(string $text, int $categoryId)
    {
        $photos = Photo::findForArticle($categoryId);
        for ($i = 0; $i < count($photos); $i++) {
            $text = str_replace(
                "[img-$i]",
                "<img src=" . $photos[$i]->getPhoto() . ">",
                $text
            );
        }

        return $text;
    }
}