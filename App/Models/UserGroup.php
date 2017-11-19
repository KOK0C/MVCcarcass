<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 19.11.2017
 * Time: 10:06
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\Model;

class UserGroup extends Model
{
    const TABLE = 'user_group';

    public $name;
}