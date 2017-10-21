<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 17.10.2017
 * Time: 16:55
 */

namespace App\Models;

use App\DataBase;
use App\Model;

/**
 * Class Article
 * @package App\Models
 * @property User $author
 * @property Category $category
 */
class Article extends Model
{
    const TABLE = 'news';

    public $title;
    public $text;
    public $date;
    private $author_id;
    private $category_id;

    public function __isset($name)
    {
        switch ($name) {
            case 'author':
                return isset($this->author_id);
                break;
            case 'category':
                return isset($this->category_id);
                break;
            default:
                return false;
        }
    }

    /**
     * @param $name
     * @return bool|object User
     * @return bool|object Category
     */
    public function __get($name)
    {
        switch ($name) {
            case 'author':
                return User::findById($this->author_id);
                break;
            case 'category':
                return Category::findById($this->category_id);
                break;
            default:
                return false;
        }
    }

    /**
     * @param string $category
     * @return array Возвращает массив с объектами Article
     */
    public static function findByCategory(): array
    {
        $dbConnect = DataBase::getInstance();
        $category = ltrim($_SERVER['REQUEST_URI'], '/');
        $sql = 'SELECT * FROM news WHERE category_id = (SELECT id FROM categories WHERE link = :link)';
        return $dbConnect->query($sql, self::class, ['link' => $category]);
    }
}