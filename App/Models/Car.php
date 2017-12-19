<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 29.10.2017
 * Time: 14:54
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\Components\Traits\File;
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
    use File;

    const TABLE = 'cars';
    const PER_PAGE = 1;

    public $model;
    public $text;
    private $icon;
    private $brand_id;
    private $img;

    private $iconDir = '/public/img/icon/';
    private $carImgDir = '/public/img/car_pic/';
    protected $fields = [
        'model' => '',
        'brand_id' => '',
        'text' => '',
        'icon' => '',
        'img' => ''
    ];

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
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws DbException
     */
    public static function findCarsByBrandPerPage(string $brand, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $sql = 'SELECT * FROM ' . self::TABLE .
                ' WHERE brand_id = (SELECT id FROM brands WHERE name = :brandName) 
               ORDER BY id DESC LIMIT ' . $perPage . ' OFFSET ' . $offset;
        return DataBase::getInstance()->query($sql, self::class, ['brandName' => $brand]);
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
     * @return int
     * @throws DbException
     */
    public static function getCountArticleForCar(string $model): int
    {
        $sql = 'SELECT COUNT(*) FROM news
                WHERE  car_id = (SELECT id FROM cars WHERE cars.model = :model)';
        return DataBase::getInstance()->countRow($sql, ['model' => $model]);
    }

    /**
     * @param string $brand
     * @return int
     * @throws DbException
     */
    public static function getCountCarByBrand(string $brand): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . self::TABLE .
            ' WHERE brand_id = (SELECT id FROM brands WHERE `name` = :brandName)';
        return DataBase::getInstance()->countRow($sql, ['brandName' => $brand]);
    }

    /**
     * @param array $data
     * @param array $rules
     * @return bool
     * @throws DbException
     */
    public function load(array $data, array $rules): bool
    {
        if ($data['img']['error'] === 0) {
            if (! $data['img'] = $this->loadFile($data['img'],  'jpeg|png|jpg|JPG|JPEG|PNG', $_SERVER['DOCUMENT_ROOT'] . $this->carImgDir)) {
                return false;
            }
        } else {
            unset($data['img']);
        }
        $pathCarIcon = $_SERVER['DOCUMENT_ROOT'] . $this->iconDir . str_replace(' ', '_', mb_strtolower(Brand::findById($data['brand_id'])->name));
        if (! file_exists($pathCarIcon)) {
            mkdir($pathCarIcon);
        }
        if ($data['icon']['error'] === 0) {
            if (! $data['icon'] = $this->loadFile(
                $data['icon'],
                'png',
                $pathCarIcon . '/')
            ) {
                return false;
            }
        } else {
            unset($data['icon']);
        }
        return parent::load($data, $rules); // TODO: Change the autogenerated stub
    }
}