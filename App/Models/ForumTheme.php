<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 03.12.2017
 * Time: 17:23
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\DataBase;

/**
 * Class ForumTheme
 * @package IhorRadchenko\App\Models
 * @property int $count
 */
class ForumTheme extends ForumSection
{
    const PER_PAGE = 2;

    /**
     * @return array
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function get5LastTheme(): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' ORDER BY id DESC LIMIT 5';
        return DataBase::getInstance()->query($sql, self::class);
    }

    /**
     * @param string $parent
     * @return array|null
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function findByParentAlias(string $parent)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE parent_id = 
        (SELECT id FROM ' . self::TABLE . ' WHERE parent_id = 0 AND alias = :parent) ORDER BY id DESC LIMIT ' . self::PER_PAGE;
        $result = DataBase::getInstance()->query($sql, self::class, ['parent' => $parent]);
        return (! empty($result)) ? $result : null;
    }

    public function __isset($name): bool
    {
        switch ($name) {
            case 'parent':
                return isset($this->parent_id);
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
            case 'parent':
                return ForumSection::findById($this->parent_id);
                break;
            case 'count':
                return DataBase::getInstance()->countRow('SELECT COUNT(*) FROM comments WHERE theme_id = :id', ['id' => $this->id]);
                break;
            default:
                return false;
        }
    }

    /**
     * @param string $parent
     * @return int
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function getCountTheme(string $parent): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . self::TABLE . ' WHERE parent_id = (SELECT id FROM ' . self::TABLE . ' WHERE alias = :alias)';
        return DataBase::getInstance()->countRow($sql, ['alias' => $parent]);
    }

    /**
     * @param int $page
     * @param int $parentId
     * @return array
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function getThemePerPage(int $page, int $parentId)
    {
        $offset = ($page - 1) * self::PER_PAGE;
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE parent_id = :id ORDER BY id DESC LIMIT ' . self::PER_PAGE . ' OFFSET ' . $offset;
        return DataBase::getInstance()->query($sql, self::class, ['id' => $parentId]);
    }

    public function getAlias(): string
    {
        return '/forum/theme/' . $this->alias;
    }

    /**
     * @param string $alias
     * @return null|self
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function findByAlias(string $alias)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE alias = :alias LIMIT 1';
        $result = DataBase::getInstance()->query($sql, self::class, ['alias' => $alias]);
        return (! empty($result)) ? $result[0] : null;
    }
}