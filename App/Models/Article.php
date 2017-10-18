<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 17.10.2017
 * Time: 16:55
 */

namespace App\Models;

use App\Model;

/**
 * Class Article
 * @package App\Models
 * @property User $author
 */
class Article extends Model
{
    const TABLE = 'news';

    public $title;
    public $text;
    public $date;
    private $author_id;

    public function __isset($name)
    {
        switch ($name) {
            case 'author':
                return isset($this->author_id);
            default:
                return false;
        }
    }

    /**
     * @param $name
     * @return bool|object User
     */
    public function __get($name)
    {
        switch ($name) {
            case 'author':
                return User::findById($this->author_id);
            default:
                return false;
        }
    }
}