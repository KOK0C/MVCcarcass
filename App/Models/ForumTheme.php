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
    /**
     * @return array
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function get5LastTheme()
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' ORDER BY id DESC LIMIT 5';
        return DataBase::getInstance()->query($sql, self::class);
    }

    public function __isset($name)
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