<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 29.10.2017
 * Time: 14:54
 */

namespace App\Models;


use App\DataBase;
use App\Model;

class Cars extends Model
{
    const TABLE = 'cars';

    public $model;
    private $icon;
    private $brand_id;

    public static function findCarsByBrand(string $brand): array
    {
        $dbConnect = new DataBase();
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE brand_id = (SELECT id FROM brands WHERE name = :brandName)';
        return $dbConnect->query($sql, self::class, ['brandName' => $brand]);
    }

    public function getIcon(): string
    {
        return '/public/img/icon/' . $this->icon;
    }

}