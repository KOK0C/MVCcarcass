<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 19.11.2017
 * Time: 10:08
 */

namespace IhorRadchenko\App\Components\Traits;

use DateTime;

trait GetDate
{
    private $date;
    private $pattern_date = '~^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$~';

    public function getDate(): string
    {
        $replacement = "[$3/$2/$1]";
        return preg_replace($this->pattern_date, $replacement, $this->date);
    }

    public function formatDate(): string
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $this->date)->format('M j, Y');
    }
}