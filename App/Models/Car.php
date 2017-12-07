<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 29.10.2017
 * Time: 14:54
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\DataBase;
use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Model;

/**
 * Class Car
 * @package App\Models
 * @property Brand $brand
 */
class Car extends Model
{
    const TABLE = 'cars';
    const PER_PAGE = 1;

    public $model;
    public $text;
    private $icon;
    private $brand_id;
    private $img;

    /**
     * @param string $brand
     * @return array|null
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function findCarsByBrand(string $brand)
    {
        $sql = 'SELECT * FROM ' . self::TABLE .
               ' WHERE brand_id = (SELECT id FROM brands WHERE name = :brandName) 
               ORDER BY model';
        $result = DataBase::getInstance()->query($sql, self::class, ['brandName' => $brand]);
        return (! empty($result)) ? $result : null;
    }

    /**
     * @param string $brand
     * @param string $model
     * @return null|self
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
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
     * @throws DbException
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

    /**
     * @param string $model
     * @param int $page
     * @return array
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function findNewsForCar(string $model, int $page): array
    {
        $offset = ($page - 1) * self::PER_PAGE;
        $sql = 'SELECT * FROM news 
                INNER JOIN car_news ON news.id = car_news.news_id 
                WHERE car_news.car_id = 
                (SELECT id FROM cars WHERE cars.model = :model) 
                ORDER BY id DESC LIMIT ' . self::PER_PAGE . ' OFFSET ' . $offset;
        return DataBase::getInstance()->query($sql, Article::class, ['model' => $model]);
    }

    /**
     * @param string $model
     * @return int
     * @throws DbException
     */
    public static function getCountArticleForCar(string $model): int
    {
        $sql = 'SELECT COUNT(*) FROM news INNER JOIN car_news ON news.id = car_news.news_id
                WHERE  car_news.car_id = (SELECT id FROM cars WHERE cars.model = :model)';
        return DataBase::getInstance()->countRow($sql, ['model' => $model]);
    }
}