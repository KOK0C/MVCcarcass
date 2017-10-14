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

    public static function findAll() : array
    {
        $dbConnect = new DataBase();
        return $dbConnect->query('SELECT * FROM ' . static::TABLE, static::class);
    }

    public static function findById(int $id) : array
    {
        $dbConnect = new DataBase();
        return $dbConnect->query(
            'SELECT * FROM ' . static::TABLE . ' WHERE user_id = :id',
            static::class,
            ['id' => $id]
        );
    }
}