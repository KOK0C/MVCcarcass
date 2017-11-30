<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 17.10.2017
 * Time: 16:55
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\Components\Traits\GetDate;
use IhorRadchenko\App\DataBase;
use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Model;

/**
 * Class Article
 * @package App\Models
 * @property Category $category
 */
class Article extends Model
{
    use GetDate;

    const TABLE = 'news';

    const PER_PAGE = 5;

    public $title;
    public $description;
    public $text;
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
     * @throws DbException
     */
    public static function findLastArticle(): array
    {
        $array = [];
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE category_id = :cat_id ORDER BY id DESC LIMIT 1';
        $categories = Category::findAll();
        foreach ($categories as $category) {
            $array[] = DataBase::getInstance()->query($sql, self::class, ['cat_id' => $category->getId()])[0];
        }
        return $array;
    }

    /**
     * @param string $link
     * @param int
     * @param bool
     * @return array Возвращает массив с объектами Article
     * @throws DbException
     */
    public static function findByCategory(string $link, int $page, bool $reversedSort = false)
    {
        $offset = ($page - 1) * self::PER_PAGE;
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE category_id =
                (SELECT c.id FROM categories `c` INNER JOIN pages p ON c.page_id = p.id
                WHERE p.link = :link)';
        if ($reversedSort === true) {
            $sql .= ' ORDER BY id DESC';
        }
        $sql .= ' LIMIT ' . self::PER_PAGE . ' OFFSET ' . $offset;
        $result = DataBase::getInstance()->query($sql, self::class, ['link' => $link]);
        return (! empty($result)) ? $result : null;
    }

    /**
     * @param string $link
     * @return int
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function getCountArticleInCategory(string $link): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . self::TABLE . ' WHERE category_id = 
                (SELECT c.id FROM categories `c` INNER JOIN pages p ON c.page_id = p.id
                WHERE p.link = :link)';
        return DataBase::getInstance()->countRow($sql, ['link' => $link]);
    }

    /**
     * @param string $link
     * @param string $alias
     * @return Article
     * @throws DbException
     */
    public static function findOneArticle(string $link, string $alias)
    {
        $sql = 'SELECT * FROM ' . self::TABLE .
               ' WHERE category_id = 
               (SELECT c.id FROM categories `c` INNER JOIN pages p ON c.page_id = p.id
                WHERE p.link = :link) 
               AND alias = :alias LIMIT 1';
        $result = DataBase::getInstance()->query($sql, self::class, ['link' => $link, 'alias' => $alias]);
        return (! empty($result)) ? $result[0] : null;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return '/public/img/articles/' . $this->image;
    }
}