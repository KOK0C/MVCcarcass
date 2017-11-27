<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 27.11.2017
 * Time: 18:07
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\Components\Traits\GetDate;
use IhorRadchenko\App\Model;

class Review extends Model
{
    use GetDate;

    const TABLE = 'reviews';

    public $text;
    private $author_id;
    private $car_id;
    public $rating;

    protected $fields = [
        'text' => '',
        'author_id' => '',
        'car_id' => '',
        'rating' => ''
    ];

    public function __isset($name)
    {
        switch ($name) {
            case 'author':
                return isset($this->author_id);
                break;
            case 'car':
                return isset($this->car_id);
                break;
            default:
                return false;
        }
    }

    /**
     * @param $name
     * @return bool|object
     */
    public function __get($name)
    {
        switch ($name) {
            case 'author':
                return User::findById($this->author_id);
                break;
            case 'car':
                return Car::findById($this->car_id);
                break;
            default:
                return false;
        }
    }
}