<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:40
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\Components\Traits\GetDate;
use IhorRadchenko\App\Model;

/**
 * Class User
 * @package App\Models
 * Реализует модель таблицы users
 */
class User extends Model
{
    use GetDate;

    const TABLE = 'users';

    public $email;
    public $hash_password;
    public $f_name;
    public $l_name;
    private $group_id;

    public function __isset($name)
    {
        switch ($name) {
            case 'group':
                return isset($this->group_id);
                break;
            default:
                return false;
        }
    }

    /**
     * @param $name
     * @return bool|string
     */
    public function __get($name)
    {
        switch ($name) {
            case 'group':
                return UserGroup::findById($this->group_id)->name;
                break;
            default:
                return false;
        }
    }
}