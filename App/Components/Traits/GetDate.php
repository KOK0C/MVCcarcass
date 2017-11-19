<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 19.11.2017
 * Time: 10:08
 */

namespace IhorRadchenko\App\Components\Traits;

trait GetDate
{
    private $date;

    public function getDate(): string
    {
        $pattern = '~^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$~';
        $replacement = "[$3/$2/$1]";
        return preg_replace($pattern, $replacement, $this->date);
    }
}