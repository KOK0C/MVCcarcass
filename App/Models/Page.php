<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 30.10.2017
 * Time: 19:37
 */

namespace App\Models;

use App\DataBase;
use App\Model;

class Page extends Model
{
    const TABLE = 'pages';

    private $link;
    public $title;
    public $description;

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return Page
     */
    public static function findByLink(string $link)
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE link = :link LIMIT 1';
        $result = $dbConnect->query($sql, self::class, ['link' => $link]);
        return array_pop($result);
    }
}