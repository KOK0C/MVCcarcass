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

    public static function findOneByMark(string $mark): self
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE `name` = :mark LIMIT 1';
        $dbConnect = DataBase::getInstance();
        return $dbConnect->query($sql, self::class, ['mark' => $mark])[0];
    }

}