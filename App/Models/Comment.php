<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 05.12.2017
 * Time: 18:10
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\Components\Traits\GetDate;
use IhorRadchenko\App\DataBase;
use IhorRadchenko\App\Model;

class Comment extends Model
{
    use GetDate;

    const TABLE = 'comments';
    const PER_PAGE = 2;

    public $text;
    private $author_id;
    private $theme_id;

    public function __isset($name): bool
    {
        switch ($name) {
            case 'author':
                return isset($this->author_id);
                break;
            case 'theme':
                return isset($this->theme_id);
                break;
            default:
                return false;
        }
    }

    /**
     * @param $name
     * @return bool|object|int
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public function __get($name)
    {
        switch ($name) {
            case 'author':
                return User::findById($this->author_id);
                break;
            case 'theme':
                return ForumTheme::findById($this->theme_id);
                break;
            default:
                return false;
        }
    }

    /**
     * @param int $id
     * @return array|null
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function getForTheme(int $id)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE theme_id = :id ORDER BY id LIMIT ' . self::PER_PAGE;
        return DataBase::getInstance()->query($sql, self::class, ['id' => $id]);
    }

    /**
     * @param int $page
     * @param int $themeId
     * @return array
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function getCommentsPerPage(int $page, int $themeId)
    {
        $offset = ($page - 1) * self::PER_PAGE;
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE theme_id = :id ORDER BY id LIMIT ' . self::PER_PAGE . ' OFFSET ' . $offset;
        return DataBase::getInstance()->query($sql, self::class, ['id' => $themeId]);
    }
}