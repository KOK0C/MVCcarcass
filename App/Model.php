<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:56
 */

namespace App;

abstract class Model
{
    const TABLE = '';

    protected $id;

    public static function findAll(): array
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . static::TABLE;
        return $dbConnect->query($sql, static::class);
    }

    public static function findById(int $id): self
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . static::TABLE . ' WHERE id = :id';
        $result = $dbConnect->query($sql, static::class, ['id' => $id]);
        return $result[0];
    }

    public static function insert()
    {

    }
}