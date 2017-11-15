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
 * @property Category $category
 */
class Article extends Model
{
    const TABLE = 'news';

    const PER_PAGE = 5;

    public $title;
    public $description;
    public $text;
    private $date;
    private $category_id;
    private $image;
    public $alias;

    public function __isset($name)
    {
        switch ($name) {
            case 'category':
                return isset($this->category_id);
                break;
            default:
                return false;
        }
    }

    /**
     * @param $name
     * @return bool|object Category
     */
    public function __get($name)
    {
        switch ($name) {
            case 'category':
                return Category::findById($this->category_id);
                break;
            default:
                return false;
        }
    }

    /**
     * Метод собирает масив с последними записями в виде объектов
     * к каждой категории для главной страницы
     * @return array Массив объектов Category
     */
    public static function findLastArticle(): array
    {
        $array = [];
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE category_id = :cat_id ORDER BY id DESC LIMIT 1';
        $categories = Category::findAll();
        foreach ($categories as $category) {
            $array[] = $dbConnect->query($sql, self::class, ['cat_id' => $category->getId()])[0];
        }
        return $array;
    }

    /**
     * @param string $link
     * @param int
     * @param bool
     * @return array Возвращает массив с объектами Article
     */
    public static function findByCategory(string $link, int $page, bool $reversedSort = false): array
    {
        $dbConnect = DataBase::getInstance();
        $offset = ($page - 1) * self::PER_PAGE;
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE category_id =
                (SELECT c.id FROM categories `c` INNER JOIN pages p ON c.page_id = p.id
                WHERE p.link = :link)';
        if ($reversedSort === true) {
            $sql .= ' ORDER BY id DESC';
        }
        $sql .= ' LIMIT ' . self::PER_PAGE . ' OFFSET ' . $offset;
        return $dbConnect->query($sql, self::class, ['link' => $link]);
    }

    public static function getCountArticleInCategory(string $link): int
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT COUNT(*) FROM ' . self::TABLE . ' WHERE category_id = 
                (SELECT c.id FROM categories `c` INNER JOIN pages p ON c.page_id = p.id
                WHERE p.link = :link)';
        return $dbConnect->countRow($sql, ['link' => $link]);
    }

    /**
     * @param string $link
     * @param string $alias
     * @return Article
     */
    public static function findOneArticle(string $link, string $alias)
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . self::TABLE .
               ' WHERE category_id = 
               (SELECT c.id FROM categories `c` INNER JOIN pages p ON c.page_id = p.id
                WHERE p.link = :link) 
               AND alias = :alias LIMIT 1';
        $result = $dbConnect->query($sql, self::class, ['link' => $link, 'alias' => $alias]);
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