<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:40
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\Model;

/**
 * Class User
 * @package App\Models
 * Реализует модель таблицы users
 */
class User extends Model
{
    const TABLE = 'users';

    public $email;
    public $hash_password;
    public $name;
}