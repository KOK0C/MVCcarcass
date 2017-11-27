<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:40
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\Components\Traits\GetDate;
use IhorRadchenko\App\DataBase;
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
    private $password;
    public $f_name;
    public $l_name;
    public $city;
    public $phone_number;
    private $group_id;

    protected $fields = [
        'email' => '',
        'password' => '',
        'f_name' => '',
        'l_name' => '',
        'phone_number' => '',
        'city' => ''
    ];

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
                return DataBase::getInstance()->get('user_group', 'id', $this->group_id)->name;
                break;
            default:
                return false;
        }
    }

    /**
     * @param string $email
     * @return null|User
     */
    public static function findByEmail(string $email)
    {
        $sql = 'SELECT * FROM ' . User::TABLE . ' WHERE email = :email LIMIT 1';
        $result = DataBase::getInstance()->query($sql, self::class, ['email' => $email]);
        return (! empty($result)) ? $result[0] : null;
    }

    public function passwordVerify(string $password) {
        if (password_verify($password, $this->password)) {
            return true;
        }
        return false;
    }

    public function passwordHash()
    {
        $this->fields['password'] = password_hash($this->fields['password'], PASSWORD_DEFAULT);
    }

    public function getFullName(): string
    {
        return trim($this->f_name . ' ' . $this->l_name);
    }

    public function recordUserSessionInDB(string $hash)
    {
        if (DataBase::getInstance()->insert(
            'user_sessions',
            ['user_id' => $this->id, 'hash_user' => $hash]
        )) {
            return true;
        }
        return false;
    }

    public static function getUserSessionFromDB($value, string $field = 'user_id')
    {
        return DataBase::getInstance()->get('user_sessions', $field, $value);
    }

    public function deleteUserSessionFromDB()
    {
        DataBase::getInstance()->delete('user_sessions', 'user_id', $this->id);
    }
}