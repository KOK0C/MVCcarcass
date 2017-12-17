<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 16.10.2017
 * Time: 16:56
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\DataBase;
use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Model;

/**
 * Class Category
 * @package App\Models
 * Реализует модель таблицы categories
 */
class Category extends Model
{
    const TABLE = 'categories';

    const PER_PAGE = 5;

    public $name;
    private $page_id;

    protected $fields = [
        'name' => '',
        'page_id' => ''
    ];

    public function __isset($name)
    {
        switch ($name) {
            case 'countNews':
                return true;
                break;
            case 'link':
                return isset($this->page_id);
                break;
            default:
                return false;
        }
    }

    /**
     * @param $name
     * @return bool|string
     * @throws DbException
     */
    public function __get($name)
    {
        switch ($name) {
            case 'countNews':
                return DataBase::getInstance()->countRow('SELECT COUNT(*) FROM news WHERE category_id = ' . $this->id);
            case 'link':
                return Page::findById($this->page_id)->getLink();
                break;
            default:
                return false;
        }
    }
}