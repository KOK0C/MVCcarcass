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
    public $description;
    public $text;
    private $date;
    private $author_id;
    private $category_id;
    private $image;

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

    public static function findLastArticle():array
    {
        $array = [];
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE category_id = :cat_id ORDER BY id DESC LIMIT 1';
        for ($i = 2; $i <= 6; $i++) {
            $array[] = $dbConnect->query($sql, self::class, ['cat_id' => $i])[0];
        }
        return $array;
    }

    /**
     * @param string $link
     * @param bool
     * @return array Возвращает массив с объектами Article
     */
    public static function findByCategory(string $link, bool $reversedSort = false): array
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . self::TABLE .
               ' WHERE category_id = (SELECT id FROM categories WHERE link = :link)';
        if ($reversedSort === true) {
            $sql .= ' ORDER BY id DESC';
        }
        return $dbConnect->query($sql, self::class, ['link' => $link]);
    }

    /**
     * @param string $link
     * @param int $id
     * @return Article
     */
    public static function findOneArticle(string $link, int $id)
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . self::TABLE .
               ' WHERE category_id = (SELECT id FROM categories WHERE link = :link) AND id = :id LIMIT 1';
        $result = $dbConnect->query($sql, self::class, ['link' => $link, 'id' => $id]);
        return array_pop($result);
    }

    public function getDate(): string
    {
        $pattern = '~^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$~';
        $replacement = "[$3/$2/$1]";
        return preg_replace($pattern, $replacement, $this->date);
    }

    public function setDate()
    {
        $this->date = date('Y-m-d H:i:s');
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return '/public/img/articles/' . $this->image;
    }
}