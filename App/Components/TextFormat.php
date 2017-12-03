<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 31.10.2017
 * Time: 18:13
 */

namespace IhorRadchenko\App\Components;

use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Models\Photo;

/**
 * Производит окончательное форматирование текста статьи перед выводом
 * Class TextFormat
 * @package App\Components
 */
class TextFormat
{

    /**
     * @param string $text
     * @param int $id
     * @return string
     * @throws DbException
     */
    public static function format(string $text, int $id): string
    {
        $text = self::changeTag($text, 'p');
        $text = self::changeTag($text, 'b');
        $text = self::createPhoto($text, $id);
        $text = self::createLink($text);
        $text = self::insertVideo($text);
        return $text;
    }

    private static function changeTag(string $text, string $tagName): string
    {
        return preg_replace(
            "~\[$tagName\](.*?)\[/$tagName\]~s",
            "<$tagName>$1</$tagName>",
            $text
        );
    }

    /**
     * Если в статье есть тег image-[0-9] заменяет его на фотографию
     * @param string $text
     * @param int $categoryId
     * @return string
     * @throws DbException
     */
    private static function createPhoto(string $text, int $categoryId): string
    {
        if (preg_match("~\[image-[0-9]\]~", $text)) {
            $photos = Photo::findForArticle($categoryId);
            for ($i = 0, $j = 1; $i < count($photos); $i++, $j++) {
                $text = str_replace(
                    "[image-$j]",
                    "<p class='fig'><img src=" . $photos[$i]->getPhoto() . "></p>",
                    $text
                );
            }
        }
        return $text;
    }

    private static function insertVideo(string $text): string
    {
        if (preg_match("~\[video-https://youtu.be/.+\]~", $text)) {
            return preg_replace(
                "~\[video-https://youtu.be/(.+)\]~",
                "<p class='video'><iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/$1\" 
                            frameborder=\"0\" allowfullscreen></iframe></p>",
                $text);
        }
        return $text;
    }

    private static function createLink(string $text): string
    {
        if (preg_match("~\[a-.+\].+\[/a\]~", $text)) {
            return preg_replace(
                "~\[a-(.+)\](.+)\[/a\]~",
                "<a href='$1'>$2</a>",
                $text);
        }
        return $text;
    }
}