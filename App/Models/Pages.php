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

class Pages extends Model
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
     * @return Pages
     */
    public static function findByLink(string $link): self
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE link = :link LIMIT 1';
        return $dbConnect->query($sql, self::class, ['link' => $link])[0];
    }
}