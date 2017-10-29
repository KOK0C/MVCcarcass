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

/**
 * Class Cars
 * @package App\Models
 * @property Brand $brand
 */
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
        return '/public/img/icon/' . str_replace(' ', '_', strtolower($this->brand->name)) . '/' . $this->icon;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        switch ($name) {
            case 'brand':
                return isset($this->brand_id);
                break;
            default:
                return false;
        }
    }

    /**
     * @param $name
     * @return bool|object
     */
    public function __get($name)
    {
        switch ($name) {
            case 'brand':
                return Brand::findById($this->brand_id);
                break;
            default:
                return false;
        }
    }

}