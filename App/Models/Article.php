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
     * @return bool|Category
     * @throws DbException
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
        $sql = 'SELECT * FROM news WHERE id IN (SELECT MAX(id) id FROM news GROUP BY category_id) ORDER BY category_id';
        return DataBase::getInstance()->query($sql, self::class);
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

    /**
     * @param string $query
     * @return array
     * @throws DbException
     */
    public static function search(string $query): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE title LIKE :querySearch OR description LIKE :querySearch OR `text` LIKE :querySearch ORDER BY id DESC';
        $query = strtr($query, ['_' => '\_', '%' => '\%']);
        $query = "%{$query}%";
        return DataBase::getInstance()->query($sql, self::class, ['querySearch' => $query]);
    }
}