<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 16.10.2017
 * Time: 16:56
 */

namespace App\Models;

use App\Model;

/**
 * Class Category
 * @package App\Models
 * Реализует модель таблицы categories
 */
class Category extends Model
{
    const TABLE = 'categories';

    public $name;
    public $link;
}