<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:40
 */

namespace App\Models;


use App\Model;

class User extends Model
{
    const TABLE = 'users';

    private $id;
    private $email;
    private $name;
    private $surname;
    private $dateRegistration;
    private $userType;
}