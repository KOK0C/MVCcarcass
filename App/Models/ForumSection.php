<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 02.12.2017
 * Time: 20:04
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\DataBase;
use IhorRadchenko\App\Model;

class ForumSection extends Model
{
    const TABLE = 'forum';

    public $title;
    protected $parent_id;
    protected $alias;
    public $count;

    protected $fields = [];

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function getMainForumSection()
    {
        $sql = 'SELECT f2.parent_id, COUNT(f1.parent_id) count, f2.title, f2.alias, f2.id FROM forum f1, forum f2
                WHERE f2.id = f1.parent_id GROUP BY f1.parent_id';
        return DataBase::getInstance()->query($sql, self::class);
    }

    /**
     * @param int $id
     * @return object
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function findById(int $id)
    {
        $sql = 'SELECT f2.parent_id, COUNT(f1.parent_id) count, f2.title, f2.alias, f2.id FROM forum f1, forum f2
                WHERE f2.id = f1.parent_id AND f2.id = :id GROUP BY f1.parent_id';
        return DataBase::getInstance()->query($sql, self::class, ['id' => $id])[0];
    }

    public function getAlias(): string
    {
        return '/forum/' . $this->alias;
    }
}