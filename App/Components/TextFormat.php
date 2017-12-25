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
     * @return string
     */
    public function bbCode(string $text): string
    {
        $text = $this->changeTag($text, 'i');
        $text = $this->changeTag($text, 'b');
        $text = $this->changeTag($text, 'u');
        $text = $this->changeTag($text, 's');
        $text = $this->createImg($text);
        $text = $this->createLink($text);
        return $text;
    }

    private function changeTag(string $text, string $tagName): string
    {
        return preg_replace(
            "~\[$tagName\](.*?)\[/$tagName\]~s",
            "<$tagName>$1</$tagName>",
            $text
        );
    }

    /**
     * @param string $text
     * @return string
     */
    private function createImg(string $text): string
    {
        return preg_replace(
            "~\[img\](.*?)\[/img\]~s",
            "<img src='$1'>",
            $text
        );
    }

    private function createLink(string $text): string
    {
        return preg_replace(
            "~\[url=(.*?)\](.*?)\[/url\]~s",
            "<a href='$1'>$2</a>",
            $text
        );
    }
}