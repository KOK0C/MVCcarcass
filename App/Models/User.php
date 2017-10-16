<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:40
 */

namespace App\Models;

use App\Model;

/**
 * Class User
 * @package App\Models
 * Реализует модель таблицы users
 * @method save
 * @method delete
 */
class User extends Model
{
    const TABLE = 'users';

    protected $email;
    protected $password;
    protected $name;
}