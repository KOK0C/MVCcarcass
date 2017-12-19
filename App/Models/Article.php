<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 17.10.2017
 * Time: 16:55
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\Components\Traits\GetDate;
use IhorRadchenko\App\Components\Traits\File;
use IhorRadchenko\App\Components\Transliterator;
use IhorRadchenko\App\DataBase;
use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Model;

/**
 * Class Article
 * @package App\Models
 * @property Category $category
 * @property Car $car
 */
class Article extends Model
{
    use GetDate, File;

    const TABLE = 'news';

    const PER_PAGE = 5;

    public $title;
    public $description;
    public $text;
    private $category_id;
    private $image;
    public $alias;
    private $car_id;

    private $uploadDir = '/public/img/articles/';
    protected $fields = [
        'title' => '',
        'description' => '',
        'category_id' => '',
        'text' => '',
        'image' => '',
        'alias' => '',
        'car_id' => 0
    ];

    public function __isset($name): bool
    {
        switch ($name) {
            case 'category':
                return isset($this->category_id);
                break;
            case 'car':
                return isset($this->car_id) && $this->car_id !== 0;
                break;
            default:
                return false;
        }
    }

    /**
     * @param $name
     * @return bool|Category|Car
     * @throws DbException
     */
    public function __get($name)
    {
        switch ($name) {
            case 'category':
                return Category::findById($this->category_id);
                break;
            case 'car':
                return Car::findById($this->car_id);
                break;
            default:
                return false;
        }
    }

    /**
     * @param array $data
     * @param array $rules
     * @return bool
     * @throws DbException
     */
    public function load(array $data, array $rules): bool
    {
        if (! empty($data['model']) && ! empty($data['mark']) && $car = Car::findCarByBrandAndModel(
                str_replace('-', ' ', $data['mark']),
                str_replace('-', ' ', $data['model'])
            )) {
            $this->fields['car_id'] = $car->getId();
        }
        if ($data['image']['error'] === 0) {
            if (! $data['image'] = $this->loadFile($data['image'],  'jpeg|png|jpg', $_SERVER['DOCUMENT_ROOT'] . $this->uploadDir)) {
                return false;
            }
        } else {
            unset($data['image']);
        }
        $this->fields['alias'] = Transliterator::ru2Lat($data['title']);
        return parent::load($data, $rules); // TODO: Change the autogenerated stub
    }

    /**
     * @param Article $article
     * @param Car $car
     * @throws DbException
     */
    public static function createRelationArticleCar(self $article, Car $car)
    {
        DataBase::getInstance()->insert('car_news', [
            'car_id' => $car->getId(),
            'news_id' => $article->getId()
        ]);
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
     * @return null|array Возвращает массив с объектами Article
     * @throws DbException
     */
    public static function findByCategory(string $link, int $page = 0, bool $reversedSort = false)
    {
        $offset = ($page - 1) * self::PER_PAGE;
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE category_id =
                (SELECT c.id FROM categories `c` INNER JOIN pages p ON c.page_id = p.id
                WHERE p.link = :link)';
        if ($reversedSort === true) {
            $sql .= ' ORDER BY id DESC';
        }
        if ($page) {
            $sql .= ' LIMIT ' . self::PER_PAGE . ' OFFSET ' . $offset;
        }
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

    /**
     * @param string $model
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function findNewsForCar(string $model, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $sql = 'SELECT * FROM news 
                WHERE car_id = 
                (SELECT id FROM cars WHERE cars.model = :model) 
                ORDER BY id DESC LIMIT ' . $perPage . ' OFFSET ' . $offset;
        return DataBase::getInstance()->query($sql, Article::class, ['model' => $model]);
    }

    /**
     * @return bool
     * @throws DbException
     */
    public function delete(): bool
    {
        $this->deleteFile($this->getImage());
        $images = $this->getImages($this->text);
        if ($images) {
            foreach ($images as $image) {
                $this->deleteFile($image);
            }
        }
        return parent::delete(); // TODO: Change the autogenerated stub
    }
}