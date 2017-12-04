<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 03.12.2017
 * Time: 17:23
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\DataBase;

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
        $sql = 'SELECT * FROM ' . static::TABLE . ' WHERE parent_id = 
        (SELECT id FROM ' . static::TABLE . ' WHERE parent_id = 0 AND alias = :parent) ORDER BY id DESC LIMIT ' . self::PER_PAGE;
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
     * @return bool|object
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public function __get($name)
    {
        switch ($name) {
            case 'parent':
                return ForumSection::findById($this->parent_id);
                break;
            default:
                return false;
        }
    }
}