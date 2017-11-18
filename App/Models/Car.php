<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 29.10.2017
 * Time: 14:54
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\DataBase;
use IhorRadchenko\App\Model;

/**
 * Class Car
 * @package App\Models
 * @property Brand $brand
 */
class Car extends Model
{
    const TABLE = 'cars';

    public $model;
    public $text;
    private $icon;
    private $brand_id;
    private $img;

    public static function findCarsByBrand(string $brand)
    {
        $sql = 'SELECT * FROM ' . self::TABLE .
               ' WHERE brand_id = (SELECT id FROM brands WHERE name = :brandName) 
               ORDER BY model';
        $result = DataBase::getInstance()->query($sql, self::class, ['brandName' => $brand]);
        return (! empty($result)) ? $result : null;
    }

    public static function findCarByBrandAndModel(string $brand, string $model)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE brand_id = (SELECT id FROM brands WHERE name = :brandName) AND model = :model LIMIT 1';
        $result = DataBase::getInstance()->query($sql, self::class, ['brandName' => $brand, 'model' => $model]);
        return (! empty($result)) ? $result[0] : null;
    }

    public function getIcon(): string
    {
        return '/public/img/icon/' . str_replace(' ', '_', strtolower($this->brand->name)) . '/' . $this->icon;
    }

    public function getImage(): string
    {
        return '/public/img/car_pic/' . $this->img;
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

    public static function findNewsForCar(string $model): array
    {
        $sql = 'SELECT * FROM news 
                INNER JOIN car_news ON news.id = car_news.news_id 
                WHERE car_news.car_id = 
                (SELECT id FROM cars WHERE cars.model = :model) 
                ORDER BY id DESC LIMIT 3';
        return DataBase::getInstance()->query($sql, Article::class, ['model' => $model]);
    }

}