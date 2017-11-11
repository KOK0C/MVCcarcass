<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 17.10.2017
 * Time: 16:56
 */

namespace App\Models;


use App\DataBase;
use App\Model;

class Brand extends Model
{
    const TABLE = 'brands';

    public $name;
    public $description;
    private $logo;

    public function getLogo(): string
    {
        return '/public/img/logo/' . $this->logo;
    }

    public static function findOneByMark(string $mark)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE `name` = :mark LIMIT 1';
        $dbConnect = DataBase::getInstance();
        $result = $dbConnect->query($sql, self::class, ['mark' => $mark]);
        return array_pop($result);
    }
}